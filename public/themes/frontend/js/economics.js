google.charts.load('current', {'packages': ['corechart']});
google.charts.setOnLoadCallback(initChart);
var global_market_id;
var global_market_text;


function resetFields()
{
    $("#period-month").val(1);
    $("#price-dropdown").val(1);
}


function drawHistoricalChart(data_values) 
{
    var formatedData = [];
    var counter = data_values.length;
    
    if (counter > 0)
    {
        
        formatedData.push(["", global_market_text]);
        var j = 1;
        for (var i in data_values)
        {
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
        formatedData.push(["", global_market_text]);
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

    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
    chart.draw(data, options);
}

function initChart()
{
    $(".market-action:first").trigger("click");
}

function generateLineGraph()
{
    $benchmark = $("select#benchmark-dropdown").val();
    $priceID = $("select#price-dropdown").val();
    $market_id = $("select#markets").val();        
    $month_id = $('select#period-month').val();

    if ($.trim($month_id) === '')
    {
        $month_id = 1;
    }                

    $url = "/api/economics/get-market-data/history/" + $("#main_country_id").val();        
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
    
    $(document).on("change", "select#price-dropdown", function () {
        generateLineGraph();
    });

    $(document).on("change", "select#period-month", function () {
        generateLineGraph();
    });

    $(document).on("change", "select#benchmark-dropdown", function () {
        generateLineGraph();
    });    
    
    $(document).on("click",".market-action",function(){
        
        resetFields();

        global_market_id = $(this).data("id");        
        global_market_text = $(this).find("h3:first").text();
        
        $(".market-chart-title").html(global_market_text);                
        
        if ($.trim(global_market_text) == "credit" || $.trim(global_market_text) == "CREDIT")
        {
            $("#price-dropdown").show();
        } else
        {
            $("#price-dropdown").hide();
        }
        
        generateLineGraph();
    });
});
