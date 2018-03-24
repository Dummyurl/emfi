google.charts.load('current', {'packages': ['corechart']});
google.charts.setOnLoadCallback(initChart);

var global_market_id;
var global_market_text;
var global_secure_id_2;
var global_secure_id_2_text;

function resetFields()
{
    $("#period-month").val(1);
}

function resetFields2()
{
    $("#period-month-2").val(1);
    $("#price-dropdown-2").val(1);
}

function initChart()
{
    $(".market-action:first").trigger("click");
    if($(".generate-bond-chart:first").size() > 0)
    $(".generate-bond-chart:first").trigger("click");
}

function fillBanchMark(data, elementID)
{
    var html = '<option value="">Add Benchmark</option>';
    for (var i in data)
    {
        html += '<option value="' + data[i]['id'] + '">' + data[i]['CUSIP'] + '</option>';
    }

    $("#"+elementID).html(html);
}

function drawBenchmarkChart(data_values)
{
    elementID = "curve_chart";
    $columnTitle = global_market_text;

    var formatedData = [];
    formatedData.push(["", {label:$columnTitle, type:'number'}, {label: $("select#benchmark-dropdown option:selected").text(), type:'number'}]);    
    
    for(var i in data_values.benchmark_history_data)
    {
       formatedData.push([data_values.benchmark_history_data[i][0],data_values.benchmark_history_data[i][1], data_values.benchmark_history_data[i][2]]);        
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
        colors: ['white', 'blue'],
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


function drawBenchmarkChart2(data_values)
{
    elementID = "curve_chart2";
    $columnTitle = global_secure_id_2_text;

    var formatedData = [];
    formatedData.push(["", {label:$columnTitle, type:'number'}, {label: $("select#benchmark-dropdown-2 option:selected").text(), type:'number'}]);    
    
    for(var i in data_values.benchmark_history_data)
    {
       formatedData.push([data_values.benchmark_history_data[i][0],data_values.benchmark_history_data[i][1], data_values.benchmark_history_data[i][2]]);        
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
        colors: ['white', 'blue'],
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


function drawChart2(data_values, elementID)
{
    var formatedData = [];

    var counter = data_values.length;
 
    $columnTitle = global_secure_id_2_text;
 
    if (counter > 0)
    {
        formatedData.push(["", $columnTitle]);
        var j = 1;
        for (var i in data_values)
        {
            if ($("select#price-dropdown-2").val() != 1)
            {
                if ($("select#price-dropdown-2").val() == 2)
                    formatedData.push([data_values[i]['created_format'], parseFloat(data_values[i]['YLD_YTM_MID'])]);
                else if ($("select#price-dropdown-2").val() == 3)
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
    var chart = new google.visualization.LineChart(document.getElementById("curve_chart2"));
    chart.draw(data, options);    
}

function drawChart(data_values, elementID)
{
    var formatedData = [];

    var counter = data_values.length;

    $columnTitle = "";

    if (counter > 0)
    {
        
        formatedData.push([$columnTitle, $columnTitle]);
        
        var j = 1;
        for (var i in data_values)
        {
            formatedData.push([data_values[i]['created_format'], parseFloat(data_values[i]['last_price'])]);
            j++;
        }
    } else
    {
        formatedData.push(["", ""]);
        formatedData.push(["", 0]);
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

function generateLineGraph2()
{
    $val = $('select#period-month-2').val();

    if ($.trim($val) == '')
    {
        $val = 1;
    }

    $benchmark = $("select#benchmark-dropdown-2").val();
    $priceID = $("select#price-dropdown-2").val();
    $market_id = 5;

    if(true)
    {
        $url = "/api/market/get-market-data/history";
        $('#AjaxLoaderDiv').fadeIn('slow');
        $.ajax({
            type: "POST",
            url: $url,
            data: {security_id: global_secure_id_2, month_id: $val, benchmark_id: $benchmark, price_id: $priceID, market_id: $market_id},
            success: function (result)
            {
                $('#AjaxLoaderDiv').fadeOut('slow');
                if (result.status == 1)
                {
                    if ($benchmark > 0)
                    {
                        drawBenchmarkChart2(result.data);
                    } else
                    {
                        drawChart2(result.data.history_data, 'curve_chart2');
                        fillBanchMark(result.data.arr_banchmark,"benchmark-dropdown-2");
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

function generateLineGraph()
{
    $benchmark = $("select#benchmark-dropdown").val();
    $priceID = 1;
    $market_id = 0;        
    $month_id = $('select#period-month').val();

    if ($.trim($month_id) === '')
    {
        $month_id = 1;
    }                

    $url = "/api/market/get-market-data/history";
    $('#AjaxLoaderDiv').fadeIn('slow');
    $.ajax({
        type: "POST",
        url: $url,
        data: {month_id: $month_id, benchmark_id: $benchmark, price_id: $priceID, security_id: global_market_id},
        success: function (result)
        {
            $('#AjaxLoaderDiv').fadeOut('slow');
            if (result.status == 1)
            {
                    if ($benchmark > 0)
                    {
                        drawBenchmarkChart(result.data);
                    } else
                    {
                        drawChart(result.data.history_data, 'curve_chart');
                        fillBanchMark(result.data.arr_banchmark,"benchmark-dropdown");
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


$(document).ready(function() {    
    
    $('.top_bg').parallax({
      imageSrc: '/themes/frontend/images/economics-bg.jpg'
    });    
    
    $(document).on("change", "select#country-combo", function () {
        window.location = '/economics/'+$(this).val();
    });

    $(document).on("change", "select#period-month", function () {
        generateLineGraph();
    });

    $(document).on("change", "select#benchmark-dropdown", function () {
        generateLineGraph();
    });
    
    
    $(document).on("change", "select#period-month-2", function () {
        generateLineGraph2();
    });

    $(document).on("change", "select#price-dropdown-2", function () {
        generateLineGraph2();
    });

    $(document).on("change", "select#benchmark-dropdown-2", function () {
        generateLineGraph2();
    });
    
    
    $(document).on("click",".generate-bond-chart",function(){
        global_secure_id_2 = $(this).data("id");
        global_secure_id_2_text = $.trim($(this).text());
        resetFields2();
        generateLineGraph2();
    });
    
    $(document).on("click",".market-action",function(){
        
        resetFields();

        global_market_id = $(this).data("id");        
        global_market_text = $(this).find("h3:first").text();
        
        $(".market-chart-title").html(global_market_text);                        
        
        generateLineGraph();
    });
});
