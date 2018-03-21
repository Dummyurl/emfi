google.charts.load('current', {'packages': ['corechart','bar']});
google.charts.setOnLoadCallback(initBarCharts);
var global_line_graph_id;
var global_historical_data;

function prepend(value, array) {
  var newArray = array.slice();
  newArray.unshift(value);
  return newArray;
}

function drawBarChart(data_values, elementID, chartType) {
    var formatedData = [];
    
    var counter = data_values.length;
    
    if(counter > 0)
    {
        formatedData.push(["",""]);    

        for (var i in data_values)
        {
            $per = parseFloat(data_values[i]['percentage_change']).toFixed(2);
            formatedData.push([data_values[i]['title'], $per]);        
        }                

// formatedData.reverse();        
//        var newArray = [];
//        newArray.push(["",""]);                    
//        // formatedData = prepend(["",""], formatedData);                
//        
//        for (var i in formatedData)
//        {
//            newArray.push(formatedData[i]);
//        }
//        var formatedData = [];
//        formatedData = newArray;
    }
    else
    {
        formatedData.push(["",""]);
        formatedData.push(["",0]);        
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
            baselineColor: '#39536b'
        },
        vAxis: {
            textStyle: {color: '#fff'},
            gridlines: {color: "#39536b"},
        }
    };
    
    var chart = new google.charts.Bar(document.getElementById(elementID));
    chart.draw(data, google.charts.Bar.convertOptions(options));
}

function drawBenchmarkChart(data_values, elementID, fromBenchMark)
{    
//    var formatedData = [];
//    for(var i in data_values.benchmark_history_data)
//    {
//        formatedData.push([data_values.benchmark_history_data[i][0],data_values.benchmark_history_data[i][1], data_values.benchmark_history_data[i][2]]);        
//    }   
//    
//    console.log(formatedData);
    
    // var data = google.visualization.arrayToDataTable(formatedData);    
    var data = google.visualization.arrayToDataTable([
          ['Year', 'Sales', 'Expenses'],
          ['2004',  1000,      400],
          ['2005',  1170,      460],
          ['2006',  660,       1120],
          ['2007',  1030,      540]
        ]);
        
    var options = {
        title: '',
        curveType: 'function',
        legend: {position: 'bottom'},
        backgroundColor: {fill: 'transparent'},
        axisTextStyle: {color: '#344b61'},
        titleTextStyle: {color: '#fff'},
        legendTextStyle: {color: '#ccc'},
        colors: ['white','red'],
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

function drawChart(data_values, elementID, fromBenchMark)
{
    var formatedData = [];    
    
    var counter = data_values.length;    
    
    if(fromBenchMark != 1)
    global_historical_data =  data_values;   
    
    if(counter > 0)
    {
        formatedData.push(["",""]);
        var j = 1;
        for (var i in data_values)
        {
            if(j == 1)
            {
                global_line_graph_id = data_values[i]['security_id'];
            }                 
            
            if($("select#price-dropdown").val() != 1 && $("select#markets").val() == 5)
            {
                if($("select#price-dropdown").val() == 2)
                    formatedData.push([data_values[i]['created_format'],parseFloat(data_values[i]['YLD_YTM_MID'])]);
                else if($("select#price-dropdown").val() == 3)
                    formatedData.push([data_values[i]['created_format'],parseFloat(data_values[i]['Z_SPRD_MID'])]);
            }
            else
            {
                formatedData.push([data_values[i]['created_format'],parseFloat(data_values[i]['last_price'])]);
            }
            
            
            j++;
        }            
    }        
    else
    {
        formatedData.push(["",""]);
        formatedData.push(["",0]);
    }
    
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
    $url = "/api/market/get-market-data/"+$marketID;
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
                }
                
                drawBarChart(result.data.top_gainer, "bar_chart", "Gainer");
                drawBarChart(result.data.top_loser, "bar_chart2", "Loser");
                
                fillBanchMark(result.data.arr_banchmark);
                
                if(result.data.gainer_history_data.length > 0)
                    drawChart(result.data.gainer_history_data, 'curve_chart',0);
                else if(result.data.loser_history_data.length > 0)
                    drawChart(result.data.loser_history_data, 'curve_chart',0);
                else
                    drawChart([], 'curve_chart',0);
            }
            else
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
        html += '<option value="'+data[i]['id']+'">'+data[i]['CUSIP']+'</option>';
    }   
    
    $("#benchmark-dropdown").html(html);
}

function generateLineGraph()
{
        $val = $('select#period-month').val();
        
        if($.trim($val) == '')
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
                data: {security_id: global_line_graph_id, month_id: $val, benchmark_id: $benchmark, price_id: $priceID,market_id: $market_id},
                success: function (result)
                {
                    $('#AjaxLoaderDiv').fadeOut('slow');
                    if (result.status == 1)
                    {      
                        if($benchmark > 0)
                        {
                            drawBenchmarkChart(result.data, 'curve_chart',1); 
                        }   
                        else
                        {
                            drawChart(result.data.history_data, 'curve_chart',0);
                            fillBanchMark(result.data.arr_banchmark);
                        }                                                
                    }
                    else
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
        else
        {
            $.bootstrapGrowl("No Data Found !", {type: 'danger', delay: 3000});
        }    
}

$(document).ready(function () {
    
    $(document).on("change","select#price-dropdown",function(){        
        generateLineGraph();
    });
    
    $(document).on("change","select#period-month",function(){
        generateLineGraph();
    });
    
    $(document).on("change","select#benchmark-dropdown",function(){
        generateLineGraph();
    });    
    
    $(document).on("change","select#markets",function(){
        $(".market-chart-title").html($(this).find("option:selected").text());            
        if($.trim($(this).find("option:selected").text()) == "credit" || $.trim($(this).find("option:selected").text()) == "CREDIT")
        {
            $("#price-dropdown").show();
        }   
        else
        {
            $("#price-dropdown").hide();
        }
        
        resetFields();
        
        initBarCharts();        
    });        
    
    $('.bg-2').parallax({
        imageSrc: '/themes/frontend/images/bg-2.jpg'
    });
});