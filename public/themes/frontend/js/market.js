google.charts.load('current', {'packages': ['corechart','bar']});
google.charts.setOnLoadCallback(initBarCharts);
var global_line_graph_id;

function drawBarChart(data_values, elementID, chartType) {
    var formatedData = [];
    
    var counter = data_values.length;
    
    if(counter > 0)
    {
        formatedData.push(["",""]);    

        for (var i in data_values)
        {
            formatedData.push([data_values[i]['title'],data_values[i]['percentage_change']]);        
        }        
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

function drawChart(data_values, elementID)
{
    var formatedData = [];
    
    var counter = data_values.length;
    
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
            
            formatedData.push([data_values[i]['created_format'],parseFloat(data_values[i]['last_price'])]);
            
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
                drawBarChart(result.data.top_gainer, "bar_chart", "Gainer");
                drawBarChart(result.data.top_loser, "bar_chart2", "Loser");
                
                if(result.data.gainer_history_data.length > 0)
                    drawChart(result.data.gainer_history_data, 'curve_chart');
                else if(result.data.loser_history_data.length > 0)
                    drawChart(result.data.loser_history_data, 'curve_chart');
                else
                    drawChart([], 'curve_chart');
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
    // $("").html('');
}

$(document).ready(function () {
    
    $(document).on("change","select#period-month",function(){
        $val = $(this).val();
        
        if($.trim($val) == '')
        {
            $val = 1;
        }
        
        if (typeof global_line_graph_id !== 'undefined')
        {
            $url = "/api/market/get-marker-data/history";
            $('#AjaxLoaderDiv').fadeIn('slow');
            $.ajax({
                type: "POST",
                url: $url,
                data: {security_id: global_line_graph_id, month_id: $val},
                success: function (result)
                {
                    $('#AjaxLoaderDiv').fadeOut('slow');
                    if (result.status == 1)
                    {                   
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