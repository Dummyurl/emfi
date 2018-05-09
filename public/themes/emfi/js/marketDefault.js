google.charts.load('current', {'packages': ['corechart', 'treemap','bar']});
google.charts.setOnLoadCallback(initChart);

var global_security_title1, global_security_title2;
var global_line_graph_id;
var global_line_graph_text;
var global_historical_data;
var global_gainer_data = [];
var global_loser_data = [];
var global_secure_id;
var first_time_click_security;
first_time_click_security = 1;

function initChart()
{
    drawTreetChart([], 'treechart_div');

    var top_gainer_data = JSON.parse($("#chart-data-gainer").html());
    top_gainer_data = JSON.parse(top_gainer_data);

    var top_loser_data = JSON.parse($("#chart-data-loser").html());
    top_loser_data = JSON.parse(top_loser_data);
    
    drawBarChart(top_gainer_data, "bar_chart", "Gainer");
    drawBarChart(top_loser_data, "bar_chart2", "Loser");        

    global_secure_id = 30; 
    loadMarketHistory();    

    // drawTreetChart([], 'treechart_div2');
    // initBarCharts();
    // initRelvalChart();
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

function drawBenchmarkChart(data_values, elementID)
{
    var tmpValues = [];
    var tmpValues2 = [];    
    result = data_values;
    data_values = data_values.data
    $columnTitle = "";
    $columnTitle2 = "";

    var formatedData = [];
    formatedData.push(["", {label:$columnTitle, type:'number'}, {label: 'tooltip', role: 'tooltip', 'p': {'html': true}}, {label: $columnTitle2, type:'number'}, {label: 'tooltip', role: 'tooltip', 'p': {'html': true}}]);    
    for(var i in data_values.benchmark_history_data)
    {
       var d = new Date(data_values.benchmark_history_data[i][3]);
       var $created = d; 

       var html1 = "<p style='white-space: nowrap;padding: 3px;'>"+result.title + "<br /> <b>" + data_values.benchmark_history_data[i][0] + ", " + data_values.benchmark_history_data[i][1] + "</b>"+"</p>";
       var html2 = "<p style='white-space: nowrap;padding: 3px;'>"+$("select#benchmark-dropdown option:selected").text() + "<br /> <b>" + data_values.benchmark_history_data[i][0] + ", " + data_values.benchmark_history_data[i][2] + "</b>"+"</p>";

       tmpValues.push(data_values.benchmark_history_data[i][1]);
       tmpValues2.push(data_values.benchmark_history_data[i][2]);

       formatedData.push
       (
            [
                {f: data_values.benchmark_history_data[i][0], v: $created},
                data_values.benchmark_history_data[i][1], 
                html1,
                data_values.benchmark_history_data[i][2],
                html2
            ]
       );        
    }   

    $minVal = 0;
    $maxVal = 5;

    $minVal2 = 0;
    $maxVal2 = 5;

    if(tmpValues.length > 0)
    {
        $minVal = Math.min.apply(null, tmpValues);
        $maxVal = Math.max.apply(null, tmpValues);
    }

    $minVal = getRoundedMinValueForYBenchmark($minVal);
    $maxVal = getRoundedMaxValueForYBenchmark($maxVal);    

    if(tmpValues2.length > 0)
    {
        $minVal2 = Math.min.apply(null, tmpValues2);
        $maxVal2 = Math.max.apply(null, tmpValues2);
    }

    $minVal2 = getRoundedMinValueForYBenchmark($minVal2);
    $maxVal2 = getRoundedMaxValueForYBenchmark($maxVal2);
    var data = google.visualization.arrayToDataTable(formatedData);


    var options = 
    {
        title: '',
        curveType: 'function',
        legend: {position: 'none'},
        tooltip: {isHtml: true},
        // vAxes: 
        // {
        //     0:
        //     {
        //         viewWindowMode:'explicit',
        //         viewWindow: 
        //         {
        //             min: $minVal,
        //             max: $maxVal,       
        //             minValue: $minVal,
        //             maxValue: $maxVal,       
        //         }                                                        
        //     },
        //     1:
        //     {
        //         gridlines: {color: "transparent"},
        //         viewWindowMode:'explicit',
        //         viewWindow: 
        //         {
        //             min: $minVal2,
        //             max: $maxVal2,
        //             minValue: $minVal2,
        //             maxValue: $maxVal2,                           
        //         }                                                        
        //     }
        // },        
        series: {
          0: {targetAxisIndex: 0},
          1: {targetAxisIndex: 1}
        },        
        backgroundColor: {fill: 'transparent'},
        axisTextStyle: {color: '#001a34'},
        titleTextStyle: {color: '#001a34'},
        legendTextStyle: {color: '#ccc'},
        colors: ['#001a34', '#666666'],
        hAxis: {
            textStyle: {color: '#001a34'},
            gridlines: {color: "transparent",count: 12}
        },
        vAxis: {
            textStyle: {color: '#001a34'},
            gridlines: {color: "#ccc"},
            baselineColor: {color: "#ccc"},            
        }
    };
    var chart = new google.visualization.LineChart(document.getElementById(elementID));
    chart.draw(data, options);

}
function loadMarketHistory()
{
    // alert(global_secure_id);
    // return false;

    $url = "/api/market/get-market-data/history";
    $month_id = $("#period-month").val();
    $benchmark = $("#benchmark-dropdown").val();
    $priceID = $("#price-dropdown").val();

    if(first_time_click_security == 1)
    {

    }
    else
    {
        $('html, body').animate({
                scrollTop: $("#linegraph-data").offset().top - 30
        }, 600);                        
    }
    first_time_click_security++;

    $('#AjaxLoaderDiv').fadeIn('slow');
    $.ajax({
        type: "POST",
        url: $url,
        data: {security_id: global_secure_id, month_id: $month_id, benchmark_id: $benchmark, price_id: $priceID, from: 'default_market'},
        success: function (result)
        {
            $('#AjaxLoaderDiv').fadeOut('slow');

            if (result.status == 1)
            {
                $(".market-chart-title-security").html(result.title);
                if($benchmark > 0)
                {
                    $(".market-chart-title-security").html(result.title +"<br /><span>"+result.title2+"</span>");
                    drawBenchmarkChart(result, "curve_chart");
                }
                else
                {
                    drawChart(result.data.history_data, 'curve_chart');
                    fillBanchMark(result.data.arr_banchmark);

                    if(result.isEquity == 1)
                    {
                        $("#price-dropdown option[value=3]").hide();
                        if($("#price-dropdown").val() == 3)
                        {
                            $("#price-dropdown").val(1);                        
                        }
                    }   
                    else
                    {
                        $("#price-dropdown option[value=3]").show();
                    } 
                }
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

function drawChart(data_values, elementID)
{
    // console.log(data_values);
    var tmpValues = [];
    var formatedData = [];

    var counter = data_values.length;


    $columnTitle = "";

    
    // 

    if (counter > 0)
    {
        $columnTitle = $columnTitle + " "+$("select#price-dropdown option:selected").data("title");
        // alert("Title: " + $columnTitle); 
        formatedData.push([$columnTitle, $columnTitle,{label: 'tooltip', role: 'tooltip', 'p': {'html': true}}]);
        var j = 1;
        for (var i in data_values)
        {

            var d = new Date(data_values[i]['created']);
            var $created = d;

            if($("select#price-dropdown").val() != 1)
            {
                if($("select#price-dropdown").val() == 2)
                {   
                    tmpValues.push(parseFloat(data_values[i]['YLD_YTM_MID']));
                    var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values[i]['title'] + "<br /> <b>" + data_values[i]['created_format'] + ", " + data_values[i]['YLD_YTM_MID'] + "</b>"+"</p>";     
                    formatedData.push([{f: data_values[i]['created_format'], v:$created}, parseFloat(data_values[i]['YLD_YTM_MID']),html]);
                }    
                else if ($("select#price-dropdown").val() == 3)
                {    
                    tmpValues.push(parseFloat(data_values[i]['Z_SPRD_MID']));
                    var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values[i]['title'] + "<br /> <b>" + data_values[i]['created_format'] + ", " + data_values[i]['YLD_YTM_MID'] + "</b>"+"</p>";    
                    formatedData.push([{f: data_values[i]['created_format'], v:$created}, parseFloat(data_values[i]['Z_SPRD_MID']),html]);
                }    
            } 
            else
            {
                tmpValues.push(parseFloat(data_values[i]['last_price']));
                var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values[i]['title'] + "<br /> <b>" + data_values[i]['created_format'] + ", " + data_values[i]['last_price'] + "</b>"+"</p>"; 
                formatedData.push([{f: data_values[i]['created_format'], v:$created}, parseFloat(data_values[i]['last_price']),html]);
            }

            j++;
        }
    } else
    {
        formatedData.push(["", ""]);
        formatedData.push(["", 0]);
    }

    // console.log(formatedData);
    $minVal = 0;
    $maxVal = 5;

    if(tmpValues.length > 0)
    {
        $minVal = Math.min.apply(null, tmpValues);
        $maxVal = Math.max.apply(null, tmpValues);
    }

    $minVal = getRoundedMinValueForY($minVal);
    $maxVal = getRoundedMaxValueForY($maxVal);    

    var data = google.visualization.arrayToDataTable(formatedData);

    var options = {
        title: '',
        curveType: 'function',
        legend: {position: 'none'},
        tooltip: {isHtml: true},
        backgroundColor: {fill: 'transparent'},
        axisTextStyle: {color: '#001a34'},
        titleTextStyle: {color: '#001a34'},
        legendTextStyle: {color: '#ccc'},
        colors: ['#001a34'],
        hAxis: {
            textStyle: {color: '#666666'},
            gridlines: {color: "transparent",count: 12}
        },
        vAxis: {
            textStyle: {color: '#666666'},
            gridlines: {color: "#ccc"},
            baselineColor: {color: "#ccc"},
            // viewWindowMode:'explicit',
            // viewWindow: 
            // {
            //     min: $minVal,
            //     max: $maxVal       
            // }                                                
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

function resetFields()
{
    $("#period-month").val(12);
    $("#price-dropdown").val(1);
    $("#price-dropdown option[value=3]").show();

    var html = '<option value="">Add Benchmark</option>';
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

            $title = data_values[i]['title'];
            $title = $title.replace("&amp;", "&");            

            if(elementID != "bar_chart")
            {
                // $val = Math.abs($per);
                $val = $per;
                formatedData.push([$title, $val, $title + ": "+$per+" %"]);
            }
            else
            {
                formatedData.push([$title, $per, $title + ": "+$per+" %"]);
            }            

            if(elementID == "bar_chart")
            global_gainer_data[$title] = data_values[i]['id'];
            else
            global_loser_data[$title] = data_values[i]['id'];    

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
        colors: ['#001a34'],
        backgroundColor: {fill: 'transparent'},
        legend: {position: 'none'},
        hAxis:
                {
                    textStyle: {color: '#666666'},
                    gridlines: {color: "#ccc"},
                    baselineColor: '#ccc',                    
                    direction: -1,
                },
        vAxis: 
        {
            textStyle: {color: '#666666'},
            gridlines: {color: "#ccc"},
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
                resetFields();
                global_secure_id = global_gainer_data[category]; 
                loadMarketHistory();
            }                
        }    
        else
        {
            if (typeof global_loser_data[category] !== 'undefined')
            {
                resetFields();
                global_secure_id = global_loser_data[category];
                loadMarketHistory();
            }                 
        }        
    });        

}

$(document).ready(function () {

    /*$('.bg-2').parallax({
        imageSrc: '/themes/frontend/images/bg-2.jpg'
    });*/

    $('select#markets').select2({
        allowClear: true,
        multiple: false,    
    });

    $(document).on("change", "select#markets", function () {
        $('#AjaxLoaderDiv').fadeIn('slow');
        window.location = $(this).find("option:selected").data("url");
    });

    $(document).on("click", ".view-security-chart", function () {
        global_secure_id = $(this).data("id");
        resetFields();
        loadMarketHistory();        
    });

    $(document).on("change", "select#period-month", function () {
        loadMarketHistory();
    });

    $(document).on("change", "select#price-dropdown", function () {
        loadMarketHistory();
    });

    $(document).on("change", "select#benchmark-dropdown", function () {

        if($.trim($(this).val()) == '' || $.trim($(this).val()) == 'Add Benchmark')
        {
            $("#benchmark-dropdown option:first").text("Add Benchmark");
        }
        else
        {
            $("#benchmark-dropdown option:first").text("Remove Benchmark");
        }

        loadMarketHistory();
    });

});	