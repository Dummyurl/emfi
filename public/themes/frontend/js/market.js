google.charts.load('current', {'packages': ['corechart']});

google.charts.load('current', {'packages': ['bar','treemap']});

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
            formatedData.push([$columnTitle, {label:'', type:'number'},{type: 'string', role: 'tooltip'}]);    
        }        
        
        for (var i in data_values)
        {
            $per = parseFloat(data_values[i]['percentage_change']).toFixed(2);



            if(elementID != "bar_chart")
            {
                // $val = Math.abs($per);
                $val = $per;
                formatedData.push([data_values[i]['title'], $val, data_values[i]['title'] + ": "+$per+" %"]);
            }
            else
            {
                formatedData.push([data_values[i]['title'], $per, data_values[i]['title'] + ": "+$per+" %"]);
            }

            

            if(elementID == "bar_chart")
            global_gainer_data[data_values[i]['title']] = data_values[i]['id'];
            else
            global_loser_data[data_values[i]['title']] = data_values[i]['id'];    

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
        colors: ['#051b34'],
        backgroundColor: {fill: 'transparent'},
        legend: {position: 'none'},
        hAxis:
                {
                    textStyle: {color: '#051b34'},
                    gridlines: {color: "#8ab3e2"},
                    baselineColor: '#8ab3e2',                    
                    direction: -1,
                },
        vAxis: 
        {
            textStyle: {color: '#051b34'},
            gridlines: {color: "#8ab3e2"},
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
                global_line_graph_text = category;
                global_line_graph_id = global_gainer_data[category];
                $("#benchmark-dropdown").html("");
                generateLineGraph(); 
                $('html, body').animate({
                        scrollTop: $("#linegraph-data").offset().top
                }, 600);                
            }                
        }    
        else
        {
            if (typeof global_loser_data[category] !== 'undefined')
            {
                global_line_graph_text = category;
                global_line_graph_id = global_loser_data[category];
                $("#benchmark-dropdown").html("");
                generateLineGraph();                    
                $('html, body').animate({
                        scrollTop: $("#linegraph-data").offset().top
                }, 600);                
            }                 
        }        
    });        

}

function drawBenchmarkChart(data_values, elementID, fromBenchMark)
{
    var tmpValues = [];
    var tmpValues2 = [];
    $columnTitle = "";
    $columnTitle2 = $("select#benchmark-dropdown option:selected").text()+ " "+$("select#price-dropdown option:selected").data("title");
    if (typeof global_line_graph_text !== 'undefined')
    {
        $columnTitle = global_line_graph_text;
        $columnTitle = $columnTitle + " "+$("select#price-dropdown option:selected").data("title");
        $(".market-chart-title-security").html(global_line_graph_text +"<br /><span>"+$("select#benchmark-dropdown option:selected").text()+"</span>");
    }  

    var formatedData = [];
    formatedData.push(["", {label:$columnTitle, type:'number'}, {label: 'tooltip', role: 'tooltip', 'p': {'html': true}}, {label: $columnTitle2, type:'number'}, {label: 'tooltip', role: 'tooltip', 'p': {'html': true}}]);    
    for(var i in data_values.benchmark_history_data)
    {
       var d = new Date(data_values.benchmark_history_data[i][3]);
       var $created = d;       

       // var html1 = '';
       // var html2 = '';

       var html1 = "<p style='white-space: nowrap;padding: 3px;'>"+global_line_graph_text + "<br /> <b>" + data_values.benchmark_history_data[i][0] + ", " + data_values.benchmark_history_data[i][1] + "</b>"+"</p>";
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

    $minVal = getRoundedMinValueForY($minVal);
    $maxVal = getRoundedMaxValue($maxVal);    

    if(tmpValues2.length > 0)
    {
        $minVal2 = Math.min.apply(null, tmpValues2);
        $maxVal2 = Math.max.apply(null, tmpValues2);
    }

    $minVal2 = getRoundedMinValueForY($minVal2);
    $maxVal2 = getRoundedMaxValue($maxVal2);    

    // console.log(formatedData);
    
//    var data = new google.visualization.DataTable();
//    data.addColumn('date', 'Month');
//    data.addColumn('number', "Average Temperature");
//    data.addColumn('number', "Average Hours of Daylight");
//
//    data.addRows([
//      [new Date(2014, 0),  -.5,  5.7],
//      [new Date(2014, 1),   .4,  8.7],
//      [new Date(2014, 2),   .5,   12],
//      [new Date(2014, 3),  2.9, 15.3],
//      [new Date(2014, 4),  6.3, 18.6],
//      [new Date(2014, 5),    9, 20.9],
//      [new Date(2014, 6), 10.6, 19.8],
//      [new Date(2014, 7), 10.3, 16.6],
//      [new Date(2014, 8),  7.4, 13.3],
//      [new Date(2014, 9),  4.4,  9.9],
//      [new Date(2014, 10), 1.1,  6.6],
//      [new Date(2014, 11), -.2,  4.5]
//    ]);    

    var data = google.visualization.arrayToDataTable(formatedData);

    // var data = google.visualization.arrayToDataTable([
    //      ['Year', $columnTitle, $("select#benchmark-dropdown option:selected").text()],
    //      ['2004', 1000, 400],
    //      ['2005', 1170, 460],
    //      ['2006', 660, 1120],
    //      ['2007', 1030, 540]
    // ]);

    // var data = new google.visualization.DataTable();
    // data.addColumn('string', '');
    // data.addColumn('string', $columnTitle);
    // data.addColumn('string', $("select#benchmark-dropdown option:selected").text());        

    // for(var i in data_values.benchmark_history_data)
    // {
    //     $val1 = parseFloat(data_values.benchmark_history_data[i][1]).toFixed(0);
    //     $val2 = parseFloat(data_values.benchmark_history_data[i][2]).toFixed(0);

    //     data.addRow([data_values.benchmark_history_data[i][0], $val1, $val2]); 
    // }             

//   var classicOptions = {
//        title: '',
//        // Gives each series an axis that matches the vAxes number below.
//        series: {
//          0: {targetAxisIndex: 0},
//          1: {targetAxisIndex: 1}
//        },
//      };


    var options = 
    {
        title: '',
        curveType: 'function',
        tooltip: {isHtml: true},
        legend: {position: 'none'},
        series: {
          0: {targetAxisIndex: 0,minValue: $minVal, maxValue: $maxVal},
          1: {targetAxisIndex: 1}
        },        
        backgroundColor: {fill: 'transparent'},
        axisTextStyle: {color: '#344b61'},
        titleTextStyle: {color: '#fff'},
        legendTextStyle: {color: '#ccc'},
        colors: ['white', 'blue'],
        hAxis: {
            textStyle: {color: '#fff'},
            gridlines: {color: "transparent",count: 12}
        },
        vAxis: 
        {
            0:
            {
                minValue: $minVal,
                maxValue: $maxVal,       
                viewWindowMode:'explicit',
                viewWindow: 
                {
                    min: $minVal,
                    max: $maxVal       
                }                                      
            },
            1:
            {
                minValue: $minVal2,
                maxValue: $maxVal2,       
                // textStyle: {color: '#fff'},
                // gridlines: {color: "#39536b"},
                // baselineColor: {color: "#39536b"},
                viewWindowMode:'explicit',
                viewWindow: 
                {
                    min: $minVal2,
                    max: $maxVal2       
                }                                        
            },
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
    var tmpValues = [];
    var counter = data_values.length;

    if (fromBenchMark != 1)
        global_historical_data = data_values;

    $columnTitle = "";

    if (typeof global_line_graph_text !== 'undefined')
    {
        $columnTitle = global_line_graph_text;
        $(".market-chart-title-security").html(global_line_graph_text);
    }               
    
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

            if (j == 1)
            {
                global_line_graph_id = data_values[i]['security_id'];
            }

            if ($("select#price-dropdown").val() != 1)
            {
                if ($("select#price-dropdown").val() == 2)
                {
                    tmpValues.push(parseFloat(data_values[i]['YLD_YTM_MID']));
                    var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values[i]['title'] + "<br /> <b>" + data_values[i]['created_format'] + ", " + data_values[i]['YLD_YTM_MID'] + "</b>"+"</p>";    
                    formatedData.push([{f: data_values[i]['created_format'], v:$created}, parseFloat(data_values[i]['YLD_YTM_MID']), html]);
                }    
                else if ($("select#price-dropdown").val() == 3)
                { 
                    tmpValues.push(parseFloat(data_values[i]['Z_SPRD_MID']));   
                    var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values[i]['title'] + "<br /> <b>" + data_values[i]['created_format'] + ", " + data_values[i]['Z_SPRD_MID'] + "</b>"+"</p>"; 
                    formatedData.push([{f: data_values[i]['created_format'], v:$created}, parseFloat(data_values[i]['Z_SPRD_MID']), html]);
                }    
            } else
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

    $minVal = 0;
    $maxVal = 5;

    if(tmpValues.length > 0)
    {
        $minVal = Math.min.apply(null, tmpValues);
        $maxVal = Math.max.apply(null, tmpValues);
    }

    // alert($minVal);
    $minVal = getRoundedMinValueForY($minVal);
    // alert($minVal);

    $maxVal = getRoundedMaxValueForY($maxVal);    

    // console.log(formatedData);

    var data = google.visualization.arrayToDataTable(formatedData);

    var options = {
        title: '',
        curveType: 'function',
        legend: {position: 'none'},
        tooltip: {isHtml: true},
        backgroundColor: {fill: 'transparent'},
        axisTextStyle: {color: '#344b61'},
        titleTextStyle: {color: '#fff'},
        legendTextStyle: {color: '#ccc'},
        colors: ['white'],
        hAxis: 
        {
            textStyle: {color: '#fff'},
            gridlines: {color: "transparent",count: 12}
        },
        vAxis: {
            textStyle: {color: '#fff'},
            gridlines: {color: "#39536b"},
            baselineColor: {color: "#39536b"},
            viewWindowMode:'explicit',
            viewWindow: 
            {
                min: $minVal,
                max: $maxVal       
            }                                    
        }
    };
    var chart = new google.visualization.LineChart(document.getElementById(elementID));
    chart.draw(data, options);
}

function initBarCharts()
{
    drawTreetChart([], 'treechart_div');

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

    initRelvalChart();
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

function drawRelvalChart(data_values) 
{
    var elementID = "curve_chart23";
    var formatedData = [];

    var counter = 1;
    var data = new google.visualization.DataTable();
    data.addColumn('string', '');

    for(j = 1;j<=1000;j++)
    {
        data.addColumn('number', '');
        data.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
        data.addColumn({type: 'string', role: 'annotation', 'p': {'html': true}});
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
            prices.push(html);
            cnt++;
        }  

        for(k = cnt+1;k<=1000;k++)
        {
            prices.push(null);            
            prices.push('');
            prices.push('');
        }

        data.addRow(prices);
    }        


    var options = {        
        curveType: 'function',
        annotations: 
        {            
            textStyle: 
            {
                    fontSize: 10,
                    // color: 'red',                                        
            }
        },        
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
            gridlines: {color: "#39536b"}
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


function resetFields()
{
    $("#period-month").val(1);
    $("#price-dropdown").val(1);
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
        $url = "/api/market/get-market-data/history";
        $('#AjaxLoaderDiv').fadeIn('slow');
        $.ajax({
            type: "POST",
            url: $url,
            data: {security_id: global_line_graph_id, month_id: $val, benchmark_id: $benchmark, price_id: $priceID, market_id: $market_id,from: 'default_market'},
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

    $(document).on("click", ".custom-market-change", function () {
        global_line_graph_text = $(this).data("name");
        global_line_graph_id = $(this).data("id");
        $("#benchmark-dropdown").html('<option value="">Add Benchmark</option>');
        generateLineGraph();                
        $('html, body').animate({
                scrollTop: $("#linegraph-data").offset().top - 50
        }, 600);                        
    });

    $(document).on("click", ".view-security-chart", function () {
        global_line_graph_text = $(this).text();
        global_line_graph_id = $(this).data("id");
        $("#benchmark-dropdown").html('<option value="">Add Benchmark</option>');
        generateLineGraph();                
        $('html, body').animate({
                scrollTop: $("#linegraph-data").offset().top - 50
        }, 600);                        
    });

    $(document).on("change", "select#period-month", function () {
        generateLineGraph();
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
        generateLineGraph();
    });

    $('select#markets').select2({
        allowClear: true,
        multiple: false,    
    });


    $(document).on("change", "select#markets", function () {
        $('#AjaxLoaderDiv').fadeIn('slow');
        window.location = $(this).find("option:selected").data("url");
        $(".market-chart-title").html($(this).find("option:selected").text());
        if ($.trim($(this).find("option:selected").text()) == "Credit")
        {
            $("#price-dropdown").show();
        } else
        {
            $("#price-dropdown").hide();
        }

        resetFields();

        initBarCharts();
    });

    $(document).on("change","#period-month-4",function(){
        initRelvalChart();
    });

    $(document).on("change","#price-dropdown-4",function(){
        initRelvalChart();
    });

    $(document).on("change","#relvalRating",function(){
        initRelvalChart();
    });

    $(document).on("change","#relvalCreditEquity",function(){

        if($(this).val() == 1)
        {
            $("#price-dropdown-4 option[value=3]").hide();
            if($("#price-dropdown-4").val() == 3)
            {
                $("#price-dropdown-4").val(1);
            }
        }
        else
        {
            $("#price-dropdown-4 option[value=3]").show();
        }

        initRelvalChart();
        $(".rel-val-sub-title").text($("#relvalCreditEquity option:selected").text())
    });        

    $(".market-chart-title").html($("select#markets").find("option:selected").text());
    
    // if ($.trim($("select#markets").find("option:selected").text()) == "Credit" || $.trim($("select#markets").find("option:selected").text()) == "CREDIT" || $("select#markets").val() == 5)
    // {
    //     $("#price-dropdown").show();
    // }
    // else
    // {
    //     $("#price-dropdown").hide();
    // }

    // $("#period-month").val(12);
    // $("#period-month").trigger("change");
});