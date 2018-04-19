google.charts.load('current', {'packages': ['corechart', 'treemap','bar']});
google.charts.setOnLoadCallback(initChart);

var global_security_title1, global_security_title2;

var global_line_graph_id;
var global_line_graph_text;
var global_historical_data;
var global_gainer_data = [];
var global_loser_data = [];


function initChart()
{
    drawTreetChart([], 'treechart_div');
    drawTreetChart([], 'treechart_div2');  
    initBarCharts();
    initRelvalChart();
}

function initRelvalChart()
{
    $url = "/api/analyzer/get-relval-chart";

    $('#AjaxLoaderDiv').fadeIn('slow');

    $relvalMonth = $("#period-month-4").val();
    $relvalPrice = $("#price-dropdown-4").val();
    $relvalRating = $("#relvalRating").val();
    $relvalCreditEquity = $("#relvalCreditEquity").val();

    $.ajax({
        type: "POST",
        url: $url,
        data: 
        {
               relvalMonth: $relvalMonth,
               relvalPrice: $relvalPrice,
               relvalRating: $relvalRating,
               relvalCreditEquity: $relvalCreditEquity
        },
        success: function (result)
        {
            $('#AjaxLoaderDiv').fadeOut('slow');
            if (result.status == 1)
            {
                drawRelvalChart(result.data);
            } 
            else
            {
                $.bootstrapGrowl(result.msg, {type: 'danger', delay: 4000});
            }
        },
        error: function (error) 
        {
            $('#AjaxLoaderDiv').fadeOut('slow');
            $.bootstrapGrowl("Internal server error !", {type: 'danger', delay: 4000});
        }
    });    

}

function drawRelvalChart(data_values) 
{
    // console.log("values");
    // console.log(data_values);

    var elementID = "curve_chart2";
    var formatedData = [];

    var counter = 1;

    // alert(counter);

    // if(counter > 0)
    // {
    //     formatedData.push(["", ""]);
        
    //     for(var i in data_values)
    //     {
    //         var prices = [];

    //         for(j in data_values[i])
    //         {
    //             prices.push(data_values[i][j]);
    //         }  

    //         formatedData.push(i, prices);            
    //     }        
    // }   
    // else
    // {
    //     formatedData.push(["", ""]);
    //     formatedData.push(["", 0]);
    // } 

    // console.log(formatedData);

    var data = new google.visualization.DataTable();
    data.addColumn('string', '');

    for(j = 1;j<=1000;j++)
    {
        data.addColumn('number', '');
        data.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
        // data.addColumn({type: 'string', role: 'annotation', 'p': {'html': true}});
    }    

    for(var i in data_values)
    {
        var prices = [];
        var cnt = 0;
        prices.push(i);



        for(j in data_values[i])
        {   
            prices.push(parseFloat(data_values[i][j]['price']));                     
            var html = '<p style="white-space: nowrap;padding: 3px;"><b>'+data_values[i][j]['country_title']+'</b><br />'+i+', '+parseFloat(data_values[i][j]['price'])+'</p>';    
            prices.push(html);
            var html = data_values[i][j]['country_code'];
            // prices.push(html);
            cnt++;
        }  

        for(k = cnt+1;k<=1000;k++)
        {
            prices.push(null);            
            prices.push('');
            // prices.push('');
        }

        data.addRow(prices);

        // data.addRow([i, prices]);
        // formatedData.push(i, prices);            
    }        


    // data.addRows([
    //   [0, 67], [1, 88], [2, 77],
    //   [3, 93], [4, 85], [5, 91],
    //   [6, 71], [7, 78], [8, 93],
    //   [9, 80], [10, 82],[0, 75],
    //   [5, 80], [3, 90], [1, 72],
    //   [5, 75], [6, 68], [7, 98],
    //   [3, 82], [9, 94], [2, 79],
    //   [2, 95], [2, 86], [3, 67],
    //   [4, 60], [2, 80], [6, 92],
    //   [2, 81], [8, 79], [9, 83],
    //   [3, 75], [1, 80], [3, 71],
    //   [3, 89], [4, 92], [5, 85],
    //   [6, 92], [7, 78], [6, 95],
    //   [3, 81], [0, 64], [4, 85],
    //   [2, 83], [3, 96], [4, 77],
    //   [5, 89], [4, 89], [7, 84],
    //   [4, 92], [9, 98]
    // ]);

    // var data = google.visualization.arrayToDataTable(formatedData);

    var options = {        
        curveType: 'function',
        tooltip: {isHtml: true},
        legend: {position: 'none'},
        backgroundColor: {fill: 'transparent'},
        axisTextStyle: {color: '#344b61'},
        titleTextStyle: {color: '#fff'},
        legendTextStyle: {color: '#ccc'},
        colors: ['white'],
        pointSize : 10,
        hAxis: 
        {
            textStyle: {color: '#fff'},
            gridlines: {color: "#39536b"},
            annotations: 
            {
                color: '#337ab7'
            }    
        },
        vAxis: 
        {
            textStyle: {color: '#fff'},
            gridlines: {color: "#39536b"},
            baselineColor: {color: "#39536b"}
        }
    };    

    var chart = new google.visualization.ScatterChart(document.getElementById(elementID));
    chart.draw(data, options);
}

function initBarCharts()
{
    $marketID = $("select#markets").val();
    $marketID = 5;
    $url = "/api/market/get-market-data/" + $marketID;
    $('#AjaxLoaderDiv').fadeIn('slow');
    $.ajax({
        type: "POST",
        url: $url,
        contentType: false,
        processData: false,
        enctype: 'multipart/form-data',
        success: function (result)
        {
            $('#AjaxLoaderDiv').fadeOut('slow');
            if (result.status == 1)
            {
                if (typeof result.data.top_gainer[0]['id'] !== 'undefined')
                {
                    global_line_graph_id = result.data.top_gainer[0]['id'];
                    global_line_graph_text = result.data.top_gainer[0]['title'];
                }

                drawBarChart(result.data.top_gainer, "bar_chart", "Gainer");
                drawBarChart(result.data.top_loser, "bar_chart2", "Loser");

                fillBanchMark(result.data.arr_banchmark);

                if (result.data.gainer_history_data.length > 0)
                    drawChart(result.data.gainer_history_data, 'curve_chart', 0);
                else if (result.data.loser_history_data.length > 0)
                    drawChart(result.data.loser_history_data, 'curve_chart', 0);
                else
                    drawChart([], 'curve_chart', 0);
            } else
            {
                $.bootstrapGrowl(result.msg, {type: 'danger', delay: 4000});
            }
        },
        error: function (error) {
            $('#AjaxLoaderDiv').fadeOut('slow');
            $.bootstrapGrowl("Internal server error !", {type: 'danger', delay: 4000});
        }
    });

}

function drawChart(data_values, elementID, fromBenchMark)
{
    // console.log(data_values);

    var formatedData = [];

    var counter = data_values.length;

    if (fromBenchMark != 1)
        global_historical_data = data_values;

    $columnTitle = "";

    if (typeof global_line_graph_text !== 'undefined')
    {
        $columnTitle = global_line_graph_text;
        $(".market-chart-title-security").html(global_line_graph_text);
    }               
    
    // 

    if (counter > 0)
    {
        $columnTitle = $columnTitle + " "+$("select#price-dropdown option:selected").data("title");
        // alert("Title: " + $columnTitle); 
        formatedData.push([$columnTitle, $columnTitle]);
        var j = 1;
        for (var i in data_values)
        {
            if (j == 1)
            {
                global_line_graph_id = data_values[i]['security_id'];
            }

            if ($("select#price-dropdown").val() != 1 && $("select#markets").val() == 5)
            {
                if ($("select#price-dropdown").val() == 2)
                    formatedData.push([data_values[i]['created_format'], parseFloat(data_values[i]['YLD_YTM_MID'])]);
                else if ($("select#price-dropdown").val() == 3)
                    formatedData.push([data_values[i]['created_format'], parseFloat(data_values[i]['Z_SPRD_MID'])]);
            } else
            {
                formatedData.push([data_values[i]['created_format'], parseFloat(data_values[i]['last_price'])]);
            }


            j++;
        }
    } else
    {
        formatedData.push(["", ""]);
        formatedData.push(["", 0]);
    }

    // console.log(formatedData);

    var data = google.visualization.arrayToDataTable(formatedData);

    var options = {
        title: '',
        curveType: 'function',
        legend: {position: 'none'},
        backgroundColor: {fill: 'transparent'},
        axisTextStyle: {color: '#344b61'},
        titleTextStyle: {color: '#fff'},
        legendTextStyle: {color: '#ccc'},
        colors: ['white'],
        hAxis: {
            textStyle: {color: '#fff'},
            gridlines: {color: "#39536b"}
        },
        vAxis: {
            textStyle: {color: '#fff'},
            gridlines: {color: "#39536b"},
            baselineColor: {color: "#39536b"}
        }
    };
    var chart = new google.visualization.LineChart(document.getElementById(elementID));
    chart.draw(data, options);
}


function fillBanchMark(data)
{
    var html = '<option value="">Add Benchmark</option>';
    for (var i in data)
    {
        html += '<option value="' + data[i]['id'] + '">' + data[i]['title'] + '</option>';
    }

    $("#benchmark-dropdown").html(html);
}


function drawBarChart(data_values, elementID, chartType) {
    var formatedData = [];

    var counter = data_values.length;

    if (counter > 0)
    {
        $columnTitle = "";

        if(elementID == "bar_chart")
        {
            global_gainer_data = [];
            formatedData.push([$columnTitle, {label:'', type:'number'},{type: 'string', role: 'tooltip'}]);
        }    
        else
        {
            global_loser_data = [];
            formatedData.push([$columnTitle, {label:'', type:'number'},{type: 'string', role: 'tooltip'}]);    
        }        
        
        for (var i in data_values)
        {
            $per = parseFloat(data_values[i]['percentage_change']).toFixed(2);



            if(elementID != "bar_chart")
            {
                $val = Math.abs($per);
                formatedData.push([data_values[i]['title'], $val, data_values[i]['title'] + ": "+$per+" %"]);
            }
            else
            {
                formatedData.push([data_values[i]['title'], $per, data_values[i]['title'] + ": "+$per+" %"]);
            }

            

            if(elementID == "bar_chart")
            global_gainer_data[data_values[i]['title']] = data_values[i]['id'];
            else
            global_loser_data[data_values[i]['title']] = data_values[i]['id'];    

        }            
    } 
    else
    {
        formatedData.push(["", "",{type: 'string', role: 'tooltip'}]);
        formatedData.push(["None", 0,""]);
    }

    // console.log(formatedData);

    var data = google.visualization.arrayToDataTable(formatedData);

    var options = {
        chart: {
            title: '',
            subtitle: '',
        },
        bars: 'horizontal', // Required for Material Bar Charts.
        colors: ['#051b34'],
        backgroundColor: {fill: 'transparent'},
        legend: {position: 'none'},
        hAxis:
                {
                    textStyle: {color: '#051b34'},
                    gridlines: {color: "#8ab3e2"},
                    baselineColor: '#8ab3e2',                    
                    direction: -1,
                },
        vAxis: 
        {
            textStyle: {color: '#051b34'},
            gridlines: {color: "#8ab3e2"},
        }
    };

    // alert("ok")    

    var chart = new google.charts.Bar(document.getElementById(elementID));
    chart.draw(data, google.charts.Bar.convertOptions(options));
    google.visualization.events.addListener(chart, 'select', function() {
        
        var selection = chart.getSelection();
        var category;
        
        category = '';
        
        for (var i = 0; i < selection.length; i++) 
        {
            var item = selection[i];
            category = data.getValue(chart.getSelection()[0].row, 0);
        }       

        if(elementID == "bar_chart")
        {
            if (typeof global_gainer_data[category] !== 'undefined')
            {
                global_line_graph_text = category;
                global_line_graph_id = global_gainer_data[category];
                $("#benchmark-dropdown").html("");
                generateLineGraph(); 
                $('html, body').animate({
                        scrollTop: $("#linegraph-data").offset().top
                }, 600);                
            }                
        }    
        else
        {
            if (typeof global_loser_data[category] !== 'undefined')
            {
                global_line_graph_text = category;
                global_line_graph_id = global_loser_data[category];
                $("#benchmark-dropdown").html("");
                generateLineGraph();                    
                $('html, body').animate({
                        scrollTop: $("#linegraph-data").offset().top
                }, 600);                
            }                 
        }        
    });        

}

$(document).ready(function () {

    $('.bg-2').parallax({
        imageSrc: '/themes/frontend/images/bg-2.jpg'
    });

    $('select#markets').select2({
        allowClear: true,
        multiple: false,    
    });

    $(document).on("change", "select#markets", function () {
        $('#AjaxLoaderDiv').fadeIn('slow');
        window.location = $(this).find("option:selected").data("url");
    });
});	