google.charts.load('current', {'packages': ['corechart', 'treemap']});
google.charts.setOnLoadCallback(initChart);

var global_security_title1, global_security_title2;

function initChart()
{
    drawTreetChart([], 'treechart_div');
    drawTreetChart([], 'treechart_div2');    
    // drawLineChart([]);
    // drawScaterChart([]);
    drawHistoryChart2([]);
}

function generateSecurityBasedChart()
{
    if(global_bond_id1 > 0 && global_bond_id2 > 0)
    {
        $("#bond-area").show();
        $('html, body').animate({
                scrollTop: $("#bond-area").offset().top - 30
        }, 600);                                        
        reInitChart();
    }   
    else
    {
        $("#bond-area").hide();
        $(".main-bond-securities").html("");
        drawAreaChart([]);
    } 
}

function reInitChart()
{        
    $url = "/api/analyzer/get-area-chart";

    $('#AjaxLoaderDiv').fadeIn('slow');

    $areaMonth = $("#period-month-2").val();
    $areaPrice = $("#price-dropdown-2").val();

    $historyMonth = $("#period-month-1").val();
    $historyPrice = $("#price-dropdown-1").val();

    $.ajax({
        type: "POST",
        url: $url,
        data: {id1: global_bond_id1, id2: global_bond_id2, areaMonth: $areaMonth, areaPrice: $areaPrice, historyMonth: $historyMonth, historyPrice: $historyPrice},
        success: function (result)
        {
            $('#AjaxLoaderDiv').fadeOut('slow');
            if (result.status == 1)
            {
                $(".main-bond-securities").html(result.main_title);
                global_security_title1 = result.global_security_title1;
                global_security_title2 = result.global_security_title2;                    
                drawHistoryChart(result.data);
                drawAreaChart(result.data.area_chart);
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

function drawAreaChart(data_values) {
    var elementID = 'area_chart';

    var formatedData = [];
    formatedData.push(["", ""]);
    if(data_values.length > 0)
    {
        for(var i in data_values)
        {
            formatedData.push([data_values[i]['created_format'], parseFloat(data_values[i]['main_price'])]);        
        }           
    }    
    else
    {
        formatedData.push(["", 0]);
    }

    // console.log(formatedData);    

    var data = google.visualization.arrayToDataTable(formatedData);
    var options = 
    {
        title: '',
        legend: 'none',
        hAxis: {title: 'Year', titleTextStyle: {color: '#333'}},
        vAxis: {minValue: 0},
        backgroundColor: {fill: 'transparent'},
        hAxis: 
        {
            textStyle: {color: '#fff'},
            gridlines: {color: "#39536b"}
        },
        vAxis: 
        {
            textStyle: {color: '#fff'},
            gridlines: {color: "#39536b"},
            baselineColor: {color: "#39536b"},
        },
        colors: ['#fff', '#8ab3e2'],
        auraColor: ['#11abc3', '#c7c3af'],
    };

    var chart = new google.visualization.AreaChart(document.getElementById(elementID));
    chart.draw(data, options);
}

function drawLineChart(data_values) {
    var data = google.visualization.arrayToDataTable([
        ['Day', 'line1', 'line2'],
        ['2004', 100, 21],
        ['2005', 570, 101],
        ['2006', 760, 1233],
        ['2007', 1210, 721],
        ['2008', 1350, 1100]
    ]);

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
        },
        colors: ['#fff', '#8ab3e2']
    };
    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
    chart.draw(data, options);
}

function drawScaterChart(data_values) {
    var data = google.visualization.arrayToDataTable([
        ['X', 'Points', 'Line'],
        [2, 3, 5],
        [4, 21.5, 20],
        [8, 29.8, null],
        [15.5, 33, 30],
        [20, 36, 35],
        [22, 44, 42],
        [35, 48, 50]
    ]);
    var options = {
        title: '',
        hAxis: {title: 'X', minValue: 0, maxValue: 60},
        vAxis: {title: 'Y', minValue: 0, maxValue: 60},
        legend: 'none',
        interpolateNulls: true,
        series: {
            1: {lineWidth: 1, pointSize: 0}
        },
        backgroundColor: {fill: 'transparent'},
        colors: ['#8ab3e2', '#fff'],
        hAxis: {
            textStyle: {color: '#fff'},
            gridlineColor: '#39536b',
            baselineColor: '#39536b',
        },
        vAxis: {
            textStyle: {color: '#fff'},
            baselineColor: '39536b',
            gridlineColor: '#39536b',
        }
    };
    var chart = new google.visualization.ScatterChart(document.getElementById('scatter_chart'));
    chart.draw(data, options);
}

function drawHistoryChart(data_values)
{

    var formatedData = [];

    var counter = data_values.benchmark_history_data;


    $columnTitle = "";
 

    if (counter > 0)
    {
        $columnTitle = "";
        $columnTitle2 = "";
        var formatedData = [];

        formatedData.push(["", {label:$columnTitle, type:'number'}, {label: $columnTitle2, type:'number'}]);    
        for(var i in data_values.benchmark_history_data)
        {
           formatedData.push([data_values.benchmark_history_data[i][0],data_values.benchmark_history_data[i][1], data_values.benchmark_history_data[i][2]]);        
        }   
    } 
    else
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
        },
        colors: ['#fff', '#8ab3e2']
    };
    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
    chart.draw(data, options);
}

function drawHistoryChart2(data_values) {
    
    var data = google.visualization.arrayToDataTable([
        ['Day', 'data1', 'data2'],
        ['2004', 100, 321],
        ['2005', 570, 400],
        ['2006', 760, 800],
        ['2007', 1210, 600],
        ['2008', 1350, 1200]
    ]);

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
        },
        colors: ['#fff', '#8ab3e2']
    };

    var chart = new google.visualization.LineChart(document.getElementById('curve_chart2'));
    chart.draw(data, options);
}


$(document).ready(function () {
    $('.top_bg').parallax({
        imageSrc: '/themes/frontend/images/economics-bg.jpg'
    });

    $(document).on("change","#period-month-2",function(){
        reInitChart();
    });

    $(document).on("change","#price-dropdown-2",function(){
        reInitChart();
    });    
});
