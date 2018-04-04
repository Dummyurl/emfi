google.charts.load('current', {'packages': ['corechart']});
google.charts.setOnLoadCallback(initChart);

var global_market_id;
var global_market_text;
var global_secure_id_2;
var global_secure_id_2_text;
var is_first;

is_first = 1;

function resetFields(chartType)
{
   if(chartType == 1)
   {
        $("#period-month-"+chartType).val($("#period-month-"+chartType+" option:first").attr('value'));
   } 
   else if(chartType == 2)
   {
        $("#period-month-"+chartType).val($("#period-month-"+chartType+" option:first").attr('value'));
   } 
   else if(chartType == 3)
   {
        $("#period-month-10").val(12);
        $("#price-dropdown-10").val(1);
        $("#benchmark-dropdown-10").html('<option value="">Add Benchmark</option>');        
   }    
}

function initChart()
{
    generateLineGraph(1);
    
    if($("#curve_chart-2").size() > 0)
    generateLineGraph(2);
    
    $(".market-action:first").trigger("click");
}

function fillBanchMark(data, elementID)
{
    var html = '<option value="">Add Benchmark</option>';
    
    for (var i in data)
    {
        html += '<option value="' + data[i]['id'] + '">' + data[i]['title'] + '</option>';
    }

    $("#"+elementID).html(html);
}

function generateLineGraph(chartType)
{
    $benchmark = $("select#benchmark-dropdown-"+chartType).val();
    $priceID = $("select#price-dropdown-"+chartType).val();
    $month_id = $('select#period-month-'+chartType).val();
    $duration = $('select#duration-dropdown-'+chartType).val();
    $country = $("#main_country_id").val();    
    $tid = $("select#benchmark-dropdown-"+chartType+" option:selected").data('tid');
    $url = "/api/economics/get-scatter-data";
    $currentTicker = $("#ticker-type-"+chartType).val();
    $('#AjaxLoaderDiv').fadeIn('slow');
    $.ajax({
        type: "POST",
        url: $url,
        data: {month_id: $month_id, benchmark_id: $benchmark, price_id: $priceID, duration: $duration, country: $country, tid: $tid, current_ticker: $currentTicker},
        success: function (result)
        {
            $('#AjaxLoaderDiv').fadeOut('slow');
            if (result.status == 1)
            {
                    if ($benchmark > 0)
                    {
                        drawBenchmarkChart(result.data, chartType);
                    } 
                    else
                    {
                        drawChart(result.data.history_data, 'curve_chart-'+chartType, chartType);
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

function drawChart(data_values, elementID, chartType)
{
    var formatedData = [];
    var counter = data_values.length;

    $("#main-chart-title-"+chartType).html($("#hid-main-chart-title-"+chartType).html());
    $columnTitle = $("#main-chart-title-"+chartType).html();

    // alert($columnTitle);

    if (counter > 0)
    {
        if(chartType == 1)
        {
            formatedData.push([{label:'', type:'number'}, $columnTitle]);
            var j = 1;
            for (var i in data_values)
            {
                if($("#duration-dropdown-1").val() == 1)
                {
                    formatedData.push([{v:parseFloat(data_values[i]['date_difference']), f:data_values[i]['category']}, parseFloat(data_values[i]['price'])]);
                }
                else
                {
                    formatedData.push([data_values[i]['category'], parseFloat(data_values[i]['price'])]);
                }                
                j++;
            }
        }   
        else if(chartType == 2)
        {
            formatedData.push([{label:'', type:'number'}, $columnTitle]);
            var j = 1;
            for (var i in data_values)
            {
                if($("#duration-dropdown-1").val() == 1)
                {
                    formatedData.push([{v:parseFloat(data_values[i]['date_difference']), f:data_values[i]['category']}, parseFloat(data_values[i]['price'])]);
                }
                else
                {
                    formatedData.push([data_values[i]['category'], parseFloat(data_values[i]['price'])]);
                }                
            }
        } 
    } 
    else
    {
        formatedData.push(["", ""]);
        formatedData.push(["", 0]);
    }

    var data = google.visualization.arrayToDataTable(formatedData);

    var options = {        
        curveType: 'function',
        legend: {position: 'none'},
        backgroundColor: {fill: 'transparent'},
        axisTextStyle: {color: '#344b61'},
        titleTextStyle: {color: '#fff'},
        legendTextStyle: {color: '#ccc'},
        colors: ['white'],
        pointSize : 10,
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
    var chart = new google.visualization.ScatterChart(document.getElementById(elementID));
    chart.draw(data, options);
}

function drawBenchmarkChart(data_values, chartType)
{
    elementID = "curve_chart-"+chartType;
    $columnTitle = $("#country-combo option:selected").text();

    $("#main-chart-title-"+chartType).html($("#hid-main-chart-title-"+chartType).html() +" VS "+ $("select#benchmark-dropdown-"+chartType+" option:selected").text());    

    var formatedData = [];
    formatedData.push(["", {label:$columnTitle, type:'number'}, {label: $("select#benchmark-dropdown-"+chartType+" option:selected").text(), type:'number'}]);

    for(var i in data_values.benchmark_history_data)
    {
       formatedData.push([data_values.benchmark_history_data[i]['title1'],data_values.benchmark_history_data[i]['price1'], data_values.benchmark_history_data[i]['price2']]);
    }

    var data = google.visualization.arrayToDataTable(formatedData);


    var options = {
        title: '',
        curveType: 'function',
        legend: {position: 'none'},        
        // series: {
        //   0: {targetAxisIndex: 0},
        //   1: {targetAxisIndex: 1}
        // },
        backgroundColor: {fill: 'transparent'},
        axisTextStyle: {color: '#344b61'},
        titleTextStyle: {color: '#fff'},
        legendTextStyle: {color: '#ccc'},
        pointSize : 10,
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
    var chart = new google.visualization.ScatterChart(document.getElementById(elementID));
    chart.draw(data, options);
}

function generateLineGraph2()
{
    $val = $('select#period-month-10').val();

    if ($.trim($val) == '')
    {
        $val = 1;
    }

    $benchmark = $("select#benchmark-dropdown-10").val();
    $priceID = $("select#price-dropdown-10").val();
    $market_id = global_market_id;

    if($market_id == 5)
        $("#price-dropdown-10").show();
    else
        $("#price-dropdown-10").hide();

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
                    }
                    else
                    {
                        drawChart2(result.data.history_data, 'curve_chart2');
                        fillBanchMark(result.data.arr_banchmark,"benchmark-dropdown-10");
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
    else
    {
        $.bootstrapGrowl("No Data Found !", {type: 'danger', delay: 3000});
    }
}

function drawChart2(data_values, elementID)
{
    var formatedData = [];

    var counter = data_values.length;

    $columnTitle = global_secure_id_2_text;

    if (counter > 0)
    {
        $columnTitle = $columnTitle + " "+$("select#price-dropdown-10 option:selected").data("title");
        formatedData.push(["", $columnTitle]);
        var j = 1;
        for (var i in data_values)
        {
            if ($("select#price-dropdown-10").val() != 1)
            {
                if ($("select#price-dropdown-10").val() == 2)
                    formatedData.push([data_values[i]['created_format'], parseFloat(data_values[i]['YLD_YTM_MID'])]);
                else if ($("select#price-dropdown-10").val() == 3)
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
        "categoryAxis": {

            "labelRotation": 80,
        },
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

function drawBenchmarkChart2(data_values)
{
    elementID = "curve_chart2";
    $columnTitle = global_secure_id_2_text+ " "+$("select#price-dropdown-10 option:selected").data("title");
    $columnTitle2 = $("select#benchmark-dropdown-10 option:selected").text()+ " "+$("select#price-dropdown-10 option:selected").data("title");

    $(".market-chart-title-2").html(global_secure_id_2_text+ " VS "+$("select#benchmark-dropdown-10 option:selected").text());
     
    var formatedData = [];
    formatedData.push(["", {label:$columnTitle, type:'number'}, {label: $columnTitle2, type:'number'}]);

    for(var i in data_values.benchmark_history_data)
    {
       formatedData.push([data_values.benchmark_history_data[i][0],data_values.benchmark_history_data[i][1], data_values.benchmark_history_data[i][2]]);
    }

    var data = google.visualization.arrayToDataTable(formatedData);

    var options = {
        title: '',
        curveType: 'function',
        series: {
          0: {targetAxisIndex: 0},
          1: {targetAxisIndex: 1}
        },
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

$(document).ready(function() {

    $('.top_bg').parallax({
      imageSrc: '/themes/frontend/images/economics-bg.jpg'
    });

    $(document).on("change", "select#country-combo", function () {
        window.location = '/economics/'+$(this).val();
    });

    $(document).on("change", "select#period-month-1", function () {
        generateLineGraph(1);
    });

    $(document).on("change", "select#duration-dropdown-1", function (){
        generateLineGraph(1);
    });

    $(document).on("change", "select#price-dropdown-1", function (){
        generateLineGraph(1);
    });    


    $(document).on("change", "select#benchmark-dropdown-1", function () {
        
        if($.trim($(this).val()) == '' || $.trim($(this).val()) == 'Add Benchmark')
        {
            $("#benchmark-dropdown-1 option:first").text("Add Benchmark");
        }
        else
        {
            $("#benchmark-dropdown-1 option:first").text("Remove Benchmark");
        }

        generateLineGraph(1);
    });


    $(document).on("change", "select#period-month-2", function () {
        generateLineGraph(2);
    });

    $(document).on("change", "select#price-dropdown-2", function () {
        generateLineGraph(2);
    });

    $(document).on("change", "select#benchmark-dropdown-2", function () {

        if($.trim($(this).val()) == '' || $.trim($(this).val()) == 'Add Benchmark')
        {
            $("#benchmark-dropdown-2 option:first").text("Add Benchmark");
        }
        else
        {
            $("#benchmark-dropdown-2 option:first").text("Remove Benchmark");
        }

        generateLineGraph(2);
    });

    $(document).on("change", "select#duration-dropdown-2", function (){
        generateLineGraph(2);
    });

    $(document).on("click",".generate-bond-chart",function(){       

       global_market_id = $(this).data("market");
       global_secure_id_2 = $(this).data("id");
       global_secure_id_2_text = $.trim($(this).text());

       resetFields(3);

       $(".market-chart-title-2").html(global_secure_id_2_text);
       generateLineGraph2();
       
       $('html, body').animate({
                scrollTop: $("#secondChartPart").offset().top
       }, 600);
    });

    $(document).on("click",".market-action",function(){

        global_market_id = $(this).data("market");
        global_secure_id_2 = $(this).data("id");
        global_secure_id_2_text = $(this).find("h3:first").text();
        resetFields(3);

        $(".market-chart-title-2").html(global_secure_id_2_text);

        if(is_first == 1)
        {
            // do nothing
        }
        else
        {
            $('html, body').animate({
                    scrollTop: $("#secondChartPart").offset().top - 30
            }, 600);
        }

        is_first++;

        generateLineGraph2();

    });

    $(document).on("change", "select#period-month-10", function () {
        generateLineGraph2();
    });

    $(document).on("change", "select#price-dropdown-10", function () {
        generateLineGraph2();
    });

    $(document).on("change", "select#benchmark-dropdown-10", function () {

        if($.trim($(this).val()) == '' || $.trim($(this).val()) == 'Add Benchmark')
        {
            $("#benchmark-dropdown-10 option:first").text("Add Benchmark");
        }
        else
        {
            $("#benchmark-dropdown-10 option:first").text("Remove Benchmark");
        }
        
        generateLineGraph2();
    });


    $('select#country-combo').select2({
        allowClear: true,
        multiple: false,    
    });    
});
