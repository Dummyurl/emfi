google.charts.load('current', {'packages': ['corechart']});

google.charts.load('current', {'packages': ['bar']});

google.charts.setOnLoadCallback(initBarCharts);

var global_line_graph_id;
var global_line_graph_text;
var global_historical_data;
var global_gainer_data = [];
var global_loser_data = [];

function prepend(value, array) {
    var newArray = array.slice();
    newArray.unshift(value);
    return newArray;
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
            formatedData.push([$columnTitle, "",{type: 'string', role: 'tooltip'}]);    
        }        
        
        for (var i in data_values)
        {
            $per = parseFloat(data_values[i]['percentage_change']).toFixed(2);
            formatedData.push([data_values[i]['title'], $per, data_values[i]['title'] + ": "+$per+" %"]);

            if(elementID == "bar_chart")
            global_gainer_data[data_values[i]['title']] = data_values[i]['id'];
            else
            global_loser_data[data_values[i]['title']] = data_values[i]['id'];    

        }            
    } 
    else
    {
        formatedData.push(["", ""]);
        formatedData.push(["", 0]);
    }

    var data = google.visualization.arrayToDataTable(formatedData);

    var options = {
        chart: {
            title: '',
            subtitle: '',
        },
        bars: 'horizontal', // Required for Material Bar Charts.
        colors: ['white'],
        backgroundColor: {fill: 'transparent'},
        legend: {position: 'none'},
        hAxis:
                {
                    textStyle: {color: '#fff'},
                    gridlines: {color: "#39536b"},
                    baselineColor: '#39536b',                    
                    direction: -1,
                },
        vAxis: 
        {
            textStyle: {color: '#fff'},
            gridlines: {color: "#39536b"}
        }
    };

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
                generateLineGraph();    
            }                
        }    
        else
        {
            if (typeof global_loser_data[category] !== 'undefined')
            {
                global_line_graph_text = category;
                global_line_graph_id = global_loser_data[category];
                generateLineGraph();    
            }                 
        }        
    });        

}

function drawBenchmarkChart(data_values, elementID, fromBenchMark)
{
    

    $columnTitle = "";

    if (typeof global_line_graph_text !== 'undefined')
    {
        $columnTitle = global_line_graph_text;
    }  

    var data = google.visualization.arrayToDataTable([
         ['Year', $columnTitle, $("select#benchmark-dropdown option:selected").text()],
         ['2004', 1000, 400],
         ['2005', 1170, 460],
         ['2006', 660, 1120],
         ['2007', 1030, 540]
    ]);

    // var data = new google.visualization.DataTable();
    // data.addColumn('string', '');
    // data.addColumn('number', $columnTitle);
    // data.addColumn('number', $("select#benchmark-dropdown option:selected").text());        

    // for(var i in data_values.benchmark_history_data)
    // {
    //     $val1 = parseFloat(data_values.benchmark_history_data[i][1]).toFixed(0);
    //     $val2 = parseFloat(data_values.benchmark_history_data[i][2]).toFixed(0);

    //     data.addRow([data_values.benchmark_history_data[i][0], $val1, $val2]); 
    // }             

    var options = {
        title: '',
        curveType: 'function',
        legend: {position: 'bottom'},
        backgroundColor: {fill: 'transparent'},
        axisTextStyle: {color: '#344b61'},
        titleTextStyle: {color: '#fff'},
        legendTextStyle: {color: '#ccc'},
        colors: ['white', 'red'],
        hAxis: {
            textStyle: {color: '#fff'},
            gridlines: {color: "#39536b"}
        },
        vAxis: {
            textStyle: {color: '#fff'},
            gridlines: {color: "#39536b"},
            baselineColor: {color: "#39536b"},            
        }
    };
    var chart = new google.visualization.LineChart(document.getElementById(elementID));
    chart.draw(data, options);

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
    }               
    
    // 

    if (counter > 0)
    {
        $columnTitle = $columnTitle + " Price";
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
        legend: {position: 'bottom'},
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

function initBarCharts()
{
    $marketID = $("select#markets").val();
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

function resetFields()
{
    $("#period-month").val(1);
    $("#price-dropdown").val(1);
}

function fillBanchMark(data)
{
    var html = '<option>Add Benchmark</option>';
    for (var i in data)
    {
        html += '<option value="' + data[i]['id'] + '">' + data[i]['CUSIP'] + '</option>';
    }

    $("#benchmark-dropdown").html(html);
}

function generateLineGraph()
{
    $val = $('select#period-month').val();

    if ($.trim($val) == '')
    {
        $val = 1;
    }

    $benchmark = $("select#benchmark-dropdown").val();
    $priceID = $("select#price-dropdown").val();
    $market_id = $("select#markets").val();

    if (typeof global_line_graph_id !== 'undefined')
    {
        $url = "/api/market/get-marker-data/history";
        $('#AjaxLoaderDiv').fadeIn('slow');
        $.ajax({
            type: "POST",
            url: $url,
            data: {security_id: global_line_graph_id, month_id: $val, benchmark_id: $benchmark, price_id: $priceID, market_id: $market_id},
            success: function (result)
            {
                $('#AjaxLoaderDiv').fadeOut('slow');
                if (result.status == 1)
                {
                    if ($benchmark > 0)
                    {
                        drawBenchmarkChart(result.data, 'curve_chart', 1);
                    } else
                    {
                        drawChart(result.data.history_data, 'curve_chart', 0);
                        fillBanchMark(result.data.arr_banchmark);
                    }
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

    } else
    {
        $.bootstrapGrowl("No Data Found !", {type: 'danger', delay: 3000});
    }
}

$(document).ready(function () {
    $('.bg-2').parallax({
        imageSrc: '/themes/frontend/images/bg-2.jpg'
    });

    $(document).on("change", "select#price-dropdown", function () {
        generateLineGraph();
    });

    $(document).on("change", "select#period-month", function () {
        generateLineGraph();
    });

    $(document).on("change", "select#benchmark-dropdown", function () {
        generateLineGraph();
    });

    $(document).on("change", "select#markets", function () {
        $(".market-chart-title").html($(this).find("option:selected").text());
        if ($.trim($(this).find("option:selected").text()) == "credit" || $.trim($(this).find("option:selected").text()) == "CREDIT")
        {
            $("#price-dropdown").show();
        } else
        {
            $("#price-dropdown").hide();
        }

        resetFields();

        initBarCharts();
    });


    $(".market-chart-title").html($("select#markets").find("option:selected").text());
    
    if ($.trim($("select#markets").find("option:selected").text()) == "credit" || $.trim($("select#markets").find("option:selected").text()) == "CREDIT")
    {
        $("#price-dropdown").show();
    }
    else
    {
        $("#price-dropdown").hide();
    }
});