google.charts.load('current', {'packages': ['corechart','bar']});
google.charts.setOnLoadCallback(initBarCharts);

function drawBarChart(data_values, elementID, chartType) {
    var formatedData = [];
    formatedData.push(["",""]);
    
    var counter = data_values.length;
    
    for (var i in data_values)
    {
        formatedData.push([chartType + " " + counter,data_values[i]['percentage_change']]);
        counter--;
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

function drawChart(data_values, elementID)
{
    var data = google.visualization.arrayToDataTable([
        ['Day', 'index'],
        ['2004', 100],
        ['2005', 570],
        ['2006', 760],
        ['2007', 1210],
        ['2008', 1350]
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
                drawBarChart(result.data.top_gainer, "bar_chart", "Gainer");
                drawBarChart(result.data.top_loser, "bar_chart2", "Loser");
                drawChart(result.data.line_graph_data, 'curve_chart');
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
    $("").html('');
}

$(document).ready(function () {
    
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