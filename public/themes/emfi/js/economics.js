google.charts.load('current', {'packages': ['corechart','treemap']});
google.charts.setOnLoadCallback(initChart);

var global_market_id;
var global_market_text;
var global_secure_id_2;
var global_secure_id_2_text;
var is_first;
var is_bond_first;
var is_first_benchmark;

is_first = 1;
is_bond_first = 1;
is_first_benchmark = 1;

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
    
    $(".generate-bond-chart.b10:first").trigger("click");
    // $(".market-action:first").trigger("click");

    drawTreetChart('treechart_div');
}


function drawRegression(data_values)
{
    var formatedData = [];
    var counter = data_values.length;
    var tmpValues = [];
    vAxisFormat ='0';
    vAxisFormat = GetDecimalFormat($("#price-dropdown-12").val());
    if(counter > 0)
    {
        formatedData.push([{label:'', type:'number'}, {label:$("#price-dropdown-3 option:selected").text(), type:'number'},{label: 'tooltip', role: 'tooltip', 'p': {'html': true}},{'type': 'string', 'role': 'style'}]);
        var j = 1;
        for (var i in data_values)
        {
            var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values[i]['created_format'] + "<br /> <b>" + data_values[i]['main_price'] + ", " + data_values[i]['main_price2'] + "</b>"+"</p>";

            $style = null;

            if(data_values[i]['is_recent'] == 1)
            {
                // $style = 'point {fill-color: #FF0000;zIndex: 99999;size: 18}';
                $style = 'point {fill-color: #FF0000;}';
            }

            tmpValues.push(parseFloat(data_values[i]['main_price']));

            formatedData.push(
                [
                    {v:parseFloat(data_values[i]['main_price']), f:data_values[i]['created_format']}, 
                    {v:parseFloat(data_values[i]['main_price2']), f:data_values[i]['created_format']},
                    html,
                    $style
                ]
            );
            j++;
        }
    }    
    else
    {
        formatedData.push(["", ""]);
        formatedData.push(["", 0]);
    }
    
    $minVal = 0;
    $maxVal = 5;


    if(tmpValues.length > 0)
    {
        $minVal = Math.min.apply(null, tmpValues);
        $maxVal = Math.max.apply(null, tmpValues);
    }

    $minVal = getRoundedMinValue($minVal);
    $maxVal = getRoundedMaxValue($maxVal);

    // console.log($minVal+" "+$maxVal);

    var data = google.visualization.arrayToDataTable(formatedData);
    var options = 
    {
        title: '',        
        tooltip: {isHtml: true},
        legend: {textStyle: {color: '#666666'}},
        hAxis: {title: '', titleTextStyle: {color: '#666666'}},
        backgroundColor: {fill: 'transparent'},
        hAxis: 
        {
            textStyle: {color: '#666666'},
            gridlines: {color: "transparent"},
            viewWindowMode:'explicit',
            viewWindow: 
            {
                min: $minVal,
                max: $maxVal        
            }            
        },
        vAxis: 
        {
            format:vAxisFormat,
            textStyle: {color: '#666666'},
            gridlines: {color: "#ccc"},
            baselineColor: {color: "#ccc"},
        },
        chartArea:{left:60,top:40,right:200,width:"100%",height:"80%"},
        colors: ['#001a34', '#666666'],
        auraColor: ['#11abc3', '#c7c3af'],        
        series: 
        {
            0: 
            {
                visibleInLegend: false
            },
            1: 
            {
                visibleInLegend: false
            }
        },        
        trendlines: 
        {
            0: 
            {
              type: 'linear',
              showR2: true,
              visibleInLegend: true
            }
        },            
      };

    var chart = new google.visualization.ScatterChart(document.getElementById('scatter_chart'));
    chart.draw(data, options);

    setTimeout(function(){
        var i = 1;
        $("#scatter_chart g [column-id='_trendline'] rect").each(function(){
            if(i != 1)
            {
                $(this).hide();
            }
            i++;
        });
    },400)
}

function fillBanchMark(data, elementID)
{
    var html = '<option value="">Add Benchmark</option>';
    
    for(var i in data)
    {
        html += '<option value="' + data[i]['id'] + '">' + data[i]['title'] + '</option>';
    }

    $("#"+elementID).html(html);

    if(elementID == "benchmark-dropdown-10")
    {
        if(is_first_benchmark == 1)
        {
            var isB30 = 0;
            var trigger_benchmark = 0;

            for(var i in data)
            {
                if(data[i]['b30_flag'] == 1 && isB30 == 0)
                {
                    isB30 = 1;
                    trigger_benchmark = data[i]['id'];
                }
            }

            if(trigger_benchmark > 0)
            {
                $("#benchmark-dropdown-10").val(trigger_benchmark);
                $("#benchmark-dropdown-10").trigger("change");
            }
            else if($("#default_benchmark_security_id").val() > 0)
            {
                $("#benchmark-dropdown-10").append('<option value="'+$("#default_benchmark_security_id").val()+'">'+$("#default_benchmark_security_title").val()+'</option>');                
                setTimeout(function(){
                    $("#benchmark-dropdown-10").val($("#default_benchmark_security_id").val());
                    $("#benchmark-dropdown-10").trigger("change");                                        
                },200);
            }
        }
        
        is_first_benchmark = is_first_benchmark + 1;   
    }    
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
                    if (result.benchmark > 0)
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

    var tmpValues = [];
    var tmpValues2 = [];

    $("#main-chart-title-"+chartType).html($("#hid-main-chart-title-"+chartType).html());
    $columnTitle = $("#main-chart-title-"+chartType).html();

    // alert($columnTitle);
    vAxisFormat ='0';
    vAxisFormat = GetDecimalFormat($("#price-dropdown-1").val());
    hAxisFormat = "0.0";

    if (counter > 0)
    {
        if(chartType == 1)
        {
            formatedData.push([{label:'', type:'number'}, $columnTitle,{label: 'tooltip', role: 'tooltip', 'p': {'html': true}}]);
            var j = 1;
            for (var i in data_values)
            {
                tmpValues2.push(parseFloat(data_values[i]['price']));
                
                if($("#duration-dropdown-1").val() == 1)
                {                    
                    tmpValues.push(parseFloat(data_values[i]['date_difference']));
                    var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values[i]['tooltip'] + "<br /> <b>" + data_values[i]['category'] + ", " + data_values[i]['price'] + "</b>"+"</p>";
                    formatedData.push([{v:parseFloat(data_values[i]['date_difference']), f:data_values[i]['category']}, parseFloat(data_values[i]['price']), html]);
                }
                else
                {
                    tmpValues.push(data_values[i]['category']);
                    var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values[i]['tooltip'] + "<br /> <b>" + data_values[i]['category'] + ", " + data_values[i]['price'] + "</b>"+"</p>";
                    formatedData.push([data_values[i]['category'], parseFloat(data_values[i]['price']), html]);
                }                
                j++;
            }
        }   
        else if(chartType == 2)
        {
            vAxisFormat = GetDecimalFormat($("#price-dropdown-"+chartType).val());
            formatedData.push([{label:'', type:'number'}, $columnTitle, {label: 'tooltip', role: 'tooltip', 'p': {'html': true}}]);
            var j = 1;
            for (var i in data_values)
            {
                tmpValues2.push(parseFloat(data_values[i]['price']));
                if($("#duration-dropdown-"+chartType).val() == 1)
                {
                    tmpValues.push(parseFloat(data_values[i]['date_difference']));
                    var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values[i]['tooltip'] + "<br /> <b>" + data_values[i]['category'] + ", " + data_values[i]['price'] + "</b>"+"</p>";
                    formatedData.push([{v:parseFloat(data_values[i]['date_difference']), f:data_values[i]['category']}, parseFloat(data_values[i]['price']), html]);
                }
                else
                {
                    tmpValues.push(data_values[i]['category']);
                    var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values[i]['tooltip'] + "<br /> <b>" + data_values[i]['category'] + ", " + data_values[i]['price'] + "</b>"+"</p>";
                    formatedData.push([data_values[i]['category'], parseFloat(data_values[i]['price']),html]);
                }                
            }
            // console.log(formatedData);
        } 
    } 
    else
    {
        formatedData.push(["", ""]);
        formatedData.push(["", 0]);
    }

    var data = google.visualization.arrayToDataTable(formatedData);

    $minVal = 0;
    $maxVal = 5;

    if(tmpValues.length > 0)
    {
        $minVal = Math.min.apply(null, tmpValues);
        $maxVal = Math.max.apply(null, tmpValues);
    }

    $minVal = getRoundedMinValue($minVal);
    $maxVal = getRoundedMaxValue($maxVal);

    $minVal2 = 0;
    $maxVal2 = 5;

    if(tmpValues2.length > 0)
    {
        $minVal2 = Math.min.apply(null, tmpValues2);
        $maxVal2 = Math.max.apply(null, tmpValues2);
    }

    $minVal2 = getRoundedMinValueForY($minVal2);
    $maxVal2 = getRoundedMaxValueForY($maxVal2);

    // console.log($minVal+" => "+$maxVal);

    var options = {        
        curveType: 'function',
        tooltip: {isHtml: true},
        legend: {position: 'none'},
        backgroundColor: {fill: 'transparent'},
        axisTextStyle: {color: '#001a34'},
        titleTextStyle: {color: '#666666'},
        legendTextStyle: {color: '#ccc'},
        colors: ['#001a34'],
        pointSize : 10,
        hAxis: 
        {
            format:hAxisFormat,
            baselineColor : 'transparent',
            textStyle: {color: '#666666'},
            gridlines: {color: "transparent"},
            viewWindowMode:'explicit',
            viewWindow: 
            {
                min: $minVal,
                max: $maxVal       
            }            
        },
        vAxis: 
        {
            format:vAxisFormat,
            textStyle: {color: '#666666'},
            gridlines: {color: "#ccc"},
            baselineColor: {color: "#ccc"},
            // viewWindowMode:'explicit',
            // viewWindow: 
            // {
            //     min: $minVal2,
            //     max: $maxVal2       
            // }                                                
        }, 
        chartArea:{left:60,top:40,right:50,width:"100%",height:"80%"}
    };
    var chart = new google.visualization.ScatterChart(document.getElementById(elementID));
    chart.draw(data, options);
}

function drawBenchmarkChart(data_values, chartType)
{
    var tmpValues = [];
    var tmpValues2 = [];
    elementID = "curve_chart-"+chartType;
    $columnTitle = $("#country-combo option:selected").text();

    $labelHTML = $.trim($("#hid-main-chart-title-"+chartType).html())+"<br/><span>"+$.trim($("select#benchmark-dropdown-"+chartType+" option:selected").text())+"</span>";
    $("#main-chart-title-"+chartType).html($.trim($labelHTML));    

    // alert("Size: "+$("#main-chart-title-"+chartType).size());
    // alert($("#main-chart-title-"+chartType).html());        
    vAxisFormat ='0';
    vAxisFormat = GetDecimalFormat($("#price-dropdown-1").val());
    hAxisFormat = "0.0";

    var formatedData = [];
    formatedData.push([{label:'', type:'number'}, {label:$columnTitle, type:'number'},{label: 'tooltip', role: 'tooltip', 'p': {'html': true}},{label: $("select#benchmark-dropdown-"+chartType+" option:selected").text(), type:'number'},{label: 'tooltip', role: 'tooltip', 'p': {'html': true}}]);
    
    // console.log("ID: "+$("#duration-dropdown-"+chartType).val());
    
    for(var i in data_values.benchmark_history_data)
    {
       if($("#duration-dropdown-"+chartType).val() == 1)
       {
            tmpValues.push(parseFloat(data_values.benchmark_history_data[i]['date_difference']));            
            var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values.benchmark_history_data[i]['tooltip'] + "<br /> <b>" + data_values.benchmark_history_data[i]['title1'] + ", " + data_values.benchmark_history_data[i]['price1'] + "</b>"+"</p>";
            var html2 = "<p style='white-space: nowrap;padding: 3px;'>"+data_values.benchmark_history_data[i]['tooltip2'] + "<br /> <b>" + data_values.benchmark_history_data[i]['title2'] + ", " + data_values.benchmark_history_data[i]['price2'] + "</b>"+"</p>";
            formatedData.push([{v:parseFloat(data_values.benchmark_history_data[i]['date_difference']), f:data_values.benchmark_history_data[i]['title1']}, data_values.benchmark_history_data[i]['price1'], html, data_values.benchmark_history_data[i]['price2'],html2]);
       }
       else
       {
            vAxisFormat = GetDecimalFormat($("#price-dropdown-"+chartType).val());

            tmpValues.push(parseFloat(data_values.benchmark_history_data[i]['title1']));
            var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values.benchmark_history_data[i]['tooltip'] + "<br /> <b>" + data_values.benchmark_history_data[i]['title1'] + ", " + data_values.benchmark_history_data[i]['price1'] + "</b>"+"</p>";
            var html2 = "<p style='white-space: nowrap;padding: 3px;'>"+data_values.benchmark_history_data[i]['tooltip2'] + "<br /> <b>" + data_values.benchmark_history_data[i]['title2'] + ", " + data_values.benchmark_history_data[i]['price2'] + "</b>"+"</p>";
            formatedData.push([data_values.benchmark_history_data[i]['title1'],data_values.benchmark_history_data[i]['price1'], html,data_values.benchmark_history_data[i]['price2'],html2]);
       } 
       
    }

    var data = google.visualization.arrayToDataTable(formatedData);
    $minVal = 0;
    $maxVal = 5;

    if(tmpValues.length > 0)
    {
        $minVal = Math.min.apply(null, tmpValues);
        $maxVal = Math.max.apply(null, tmpValues);
    }

    $minVal = getRoundedMinValue($minVal);
    $maxVal = getRoundedMaxValue($maxVal);    

    var options = {
        title: '',
        curveType: 'function',
        legend: {position: 'none'},
        tooltip: {isHtml: true},        
        // series: {
        //   0: {targetAxisIndex: 0},
        //   1: {targetAxisIndex: 1}
        // },
        backgroundColor: {fill: 'transparent'},
        axisTextStyle: {color: '#666666'},
        titleTextStyle: {color: '#666666'},
        legendTextStyle: {color: '#ccc'},
        pointSize : 10,
        colors: ['#001a34', '#666666'],
        hAxis: 
        {
            format:hAxisFormat,
            baselineColor : 'transparent',
            textStyle: {color: '#666666'},
            gridlines: {color: "transparent"},
            viewWindowMode:'explicit',
            viewWindow: 
            {
                min: $minVal,
                max: $maxVal       
            }            
        },
        vAxis: 
        {
            format:vAxisFormat,
            textStyle: {color: '#666666'},
            gridlines: {color: "#ccc"},
            baselineColor: {color: "#ccc"},
        }, 
        chartArea:{left:60,top:40,right:60,width:"100%",height:"80%"}
    };
    var chart = new google.visualization.ScatterChart(document.getElementById(elementID));
    chart.draw(data, options);
}

function drawAreaChart(data_values) {
    var elementID = 'area_chart';

    var formatedData = [];
    var tmpValues = [];
    vAxisFormat ='0';
    vAxisFormat = GetDecimalFormat($("#price-dropdown-11").val());
    
    if(data_values.length > 0)
    {
        formatedData.push(["", ""]);
        for(var i in data_values)
        {
            tmpValues.push(parseFloat(data_values[i]['main_price']));
            var d = new Date(data_values[i]['created']);
            var $created = d;           
            formatedData.push([{f: data_values[i]['created_format'], v:$created}, parseFloat(data_values[i]['main_price'])]);        
        }           
    }    
    else
    {
        formatedData.push(["", ""]);
        formatedData.push(["", 0]);
    }

    $minVal = 0;
    $maxVal = 5;

    if(tmpValues.length > 0)
    {
        $minVal = Math.min.apply(null, tmpValues);
        $maxVal = Math.max.apply(null, tmpValues);
    }

    // alert($minVal);
    $minVal = getRoundedMinValueForY($minVal);
    $maxVal = getRoundedMaxValueForY($maxVal);    


    // console.log(formatedData);    

    var data = google.visualization.arrayToDataTable(formatedData);
    var options = 
    {
        title: '',
        legend: 'none',
        hAxis: {title: '', titleTextStyle: {color: '#666666'}},
        // vAxis: {minValue: 0},
        backgroundColor: {fill: 'transparent'},
        hAxis: 
        {
            textStyle: {color: '#666666'},
            gridlines: {color: "transparent",count: 12}
        },
        vAxis: 
        {
            format:vAxisFormat,
            textStyle: {color: '#666666'},
            gridlines: {color: "#ccc"},
            baselineColor: {color: "#ccc"},
            // viewWindowMode:'explicit',
            // viewWindow: 
            // {
            //     min: $minVal,
            //     max: $maxVal       
            // }            
        },
        chartArea:{left:70,top:40,right:50,width:"100%",height:"80%"},
        colors: ['#001a34', '#666666'],
        auraColor: ['#001a34', '#666666'],
    };

    var chart = new google.visualization.AreaChart(document.getElementById(elementID));
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

    if(is_bond_first == 1 && $market_id == 5)
    {
        is_bond_first++;   
        $("select#price-dropdown-10").val(3);
        $priceID = $("select#price-dropdown-10").val();
    }        

    if(true)
    {
        $url = "/api/market/get-market-data/history";
        $('#AjaxLoaderDiv').fadeIn('slow');
        $.ajax({
            type: "POST",
            url: $url,
            data: {
                    security_id: global_secure_id_2, month_id: $val, benchmark_id: $benchmark, 
                    price_id: $priceID, market_id: $market_id, with_sub_data: 1,
                    areaMonth: $("#period-month-11").val(),
                    areaPrice: $("#price-dropdown-11").val(),
                    regressionMonth: $("#period-month-12").val(),
                    regressionPrice: $("#price-dropdown-12").val(),
                  },
            success: function (result)
            {
                $('#AjaxLoaderDiv').fadeOut('slow');

                if (result.status == 1)
                {
                    if ($benchmark > 0)
                    {
                        $("#bond-area").show();
                        drawBenchmarkChart2(result.data);
                        $html = $(".main-bond-securities").html($(".market-chart-title-2").html());
                        drawRegression(result.data.regression_data);
                        drawAreaChart(result.data.areachart_data);                        
                    }
                    else
                    {
                        $html = $(".market-chart-title-2").html(global_secure_id_2_text);
                        $("#bond-area").hide();
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
    var tmpValues = [];
    vAxisFormat ='0';
    vAxisFormat = GetDecimalFormat($("#price-dropdown-10").val());
    hAxisFormat = "0.0";

    if (counter > 0)
    {
        $columnTitle = $columnTitle + " "+$("select#price-dropdown-10 option:selected").data("title");
        formatedData.push(["", $columnTitle,{label: 'tooltip', role: 'tooltip', 'p': {'html': true}}]);

        var j = 1;
        for (var i in data_values)
        {

           var d = new Date(data_values[i]['created']);
           var $created = d;           

            if ($("select#price-dropdown-10").val() != 1)
            {                
                if ($("select#price-dropdown-10").val() == 2)
                {
                    tmpValues.push(parseFloat(data_values[i]['YLD_YTM_MID']));
                    var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values[i]['title'] + "<br /> <b>" + data_values[i]['created_format'] + ", " + data_values[i]['YLD_YTM_MID'] + "</b>"+"</p>";    
                    formatedData.push([{f: data_values[i]['created_format'], v:$created}, parseFloat(data_values[i]['YLD_YTM_MID']),html]);
                }    
                else if ($("select#price-dropdown-10").val() == 3)
                {
                    tmpValues.push(parseFloat(data_values[i]['Z_SPRD_MID']));
                    var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values[i]['title'] + "<br /> <b>" + data_values[i]['created_format'] + ", " + data_values[i]['Z_SPRD_MID'] + "</b>"+"</p>";
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

    var data = google.visualization.arrayToDataTable(formatedData);
    $minVal = 0;
    $maxVal = 5;

    if(tmpValues.length > 0)
    {
        $minVal = Math.min.apply(null, tmpValues);
        $maxVal = Math.max.apply(null, tmpValues);
    }

    // alert($minVal);
    $minVal = getRoundedMinValueForY($minVal);
    $maxVal = getRoundedMaxValueForY($maxVal);    


    var options = {
        title: '',
        tooltip: {isHtml: true},
        curveType: 'function',
        "categoryAxis": {

            "labelRotation": 80,
        },
        legend: {position: 'none'},
        backgroundColor: {fill: 'transparent'},
        axisTextStyle: {color: '#001a34'},
        titleTextStyle: {color: '#666666'},
        legendTextStyle: {color: '#ccc'},
        colors: ['#001a34'],
        hAxis: {
            textStyle: {color: '#666666'},
            gridlines: {color: "transparent",count: 12}
        },
        vAxis: 
        {
            format:vAxisFormat,
            textStyle: {color: '#001a34'},
            gridlines: {color: "#ccc"},
            baselineColor: {color: "#ccc"},
            // viewWindowMode:'explicit',
            // viewWindow: 
            // {
            //     min: $minVal,
            //     max: $maxVal       
            // }            
        }, 
        chartArea:{left:60,top:40,right:50,width:"100%",height:"80%"}
    };
    var chart = new google.visualization.LineChart(document.getElementById("curve_chart2"));
    chart.draw(data, options);
}

function drawBenchmarkChart2(data_values)
{
    var tmpValues = [];
    var tmpValues2 = [];    
    elementID = "curve_chart2";
    $columnTitle = global_secure_id_2_text+ " "+$("select#price-dropdown-10 option:selected").data("title");
    $columnTitle2 = $("select#benchmark-dropdown-10 option:selected").text()+ " "+$("select#price-dropdown-10 option:selected").data("title");
    vAxisFormat ='0';
    vAxisFormat = GetDecimalFormat($("#price-dropdown-10").val());

    $(".market-chart-title-2").html(global_secure_id_2_text+ "<br /><span>"+$("select#benchmark-dropdown-10 option:selected").text()+"</span>");
     
    var formatedData = [];
    formatedData.push(["", {label:$columnTitle, type:'number'}, {label: 'tooltip', role: 'tooltip', 'p': {'html': true}}, {label: $columnTitle2, type:'number'}, {label: 'tooltip', role: 'tooltip', 'p': {'html': true}}]);

    for(var i in data_values.benchmark_history_data)
    {
       tmpValues.push(data_values.benchmark_history_data[i][1]);
       tmpValues2.push(data_values.benchmark_history_data[i][2]);

       var d = new Date(data_values.benchmark_history_data[i][3]);
       var $created = d;        

       var html1 = "<p style='white-space: nowrap;padding: 3px;'>"+global_secure_id_2_text + "<br /> <b>" + data_values.benchmark_history_data[i][0] + ", " + data_values.benchmark_history_data[i][1] + "</b>"+"</p>";
       var html2 = "<p style='white-space: nowrap;padding: 3px;'>"+$("select#benchmark-dropdown-10 option:selected").text() + "<br /> <b>" + data_values.benchmark_history_data[i][0] + ", " + data_values.benchmark_history_data[i][2] + "</b>"+"</p>";

       formatedData.push([{f: data_values.benchmark_history_data[i][0], v: $created},data_values.benchmark_history_data[i][1], html1, data_values.benchmark_history_data[i][2], html2]);
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

    var options = {
        title: '',
        curveType: 'function',
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
        series: 
        {
          0: {targetAxisIndex: 0},
          1: {targetAxisIndex: 1}
        },
        legend: {position: 'none'},
        backgroundColor: {fill: 'transparent'},
        axisTextStyle: {color: '#001a34'},
        titleTextStyle: {color: '#666666'},
        legendTextStyle: {color: '#ccc'},
        colors: ['#001a34', '#666666'],
        hAxis: {
            textStyle: {color: '#666666'},
            gridlines: {color: "transparent",count: 12}
        },
        vAxis: 
        {
            format:vAxisFormat,
            textStyle: {color: '#666666'},
            gridlines: {color: "#ccc"},
            baselineColor: {color: "#ccc"},
        },
        vAxes: {
              0: { textStyle:{color: '#001a34'} },
              1: { textStyle:{color: '#666666'} }
        },
        chartArea:{left:60,top:40,right:60,width:"100%",height:"80%"}
    };    

    var chart = new google.visualization.LineChart(document.getElementById(elementID));
    chart.draw(data, options);
}

$(document).ready(function() {

/*    $('.top_bg').parallax({
      imageSrc: '/themes/frontend/images/economics-bg.jpg'
    });*/

    $(document).on("change", "select#country-combo", function () {
        window.location = '/'+$('#main_lang').val()+'/'+$(this).val();
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
       $("#price-dropdown-10").val(3);

       $(".market-chart-title-2").html(global_secure_id_2_text);
       generateLineGraph2();
       

        if(is_first == 1)
        {
            // do nothing
        }
        else
        {
           $('html, body').animate({
                    scrollTop: $("#secondChartPart").offset().top - 67
           }, 600);
        }

        is_first++;       
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
                    scrollTop: $("#secondChartPart").offset().top - 67
            }, 600);
        }

        is_first++;

        generateLineGraph2();

    });

    $(document).on("change", "select#period-month-10", function () {
        generateLineGraph2();
    });

    $(document).on("change", "select#price-dropdown-11", function () {
        generateLineGraph2();
    });

    $(document).on("change", "select#period-month-11", function () {
        generateLineGraph2();
    });

    $(document).on("change", "select#period-month-12", function () {
        generateLineGraph2();
    });

    $(document).on("change", "select#price-dropdown-12", function () {
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
