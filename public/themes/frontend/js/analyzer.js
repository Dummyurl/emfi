google.charts.load('current', {'packages': ['corechart', 'treemap']});
google.charts.setOnLoadCallback(initChart);

var global_security_title1, global_security_title2;

function initChart()
{
    drawTreetChart([], 'treechart_div');
    drawTreetChart([], 'treechart_div2');  
    initRelvalChart();  
    // drawRegression([]);
    // drawRelvalChart([]);
}

function navigateToDiv(page_type)
{
    // alert(page_type)
    
    if($("#"+page_type).size())
    {
        $('html, body').animate({
                scrollTop: $("#"+page_type).offset().top - 30
        }, 600);                                            
    }
}

function generateSecurityBasedChart(isScrollDown)
{
    if(global_bond_id1 > 0 && global_bond_id2 > 0)
    {
        // alert(global_bond_id1 +" "+ global_bond_id2);

        $("#bond-area").show();

        if(isScrollDown == 1)
        {
            $('html, body').animate({
                    scrollTop: $("#bond-area").offset().top - 30
            }, 600);                                                    
        }    
        

        $("#price-dropdown-1").val(3);
        $("#price-dropdown-2").val(3);
        $("#price-dropdown-3").val(3);

        $("#period-month-1").val($("#period-month-1 option[value=12]").attr("value"));
        $("#period-month-2").val($("#period-month-2 option[value=12]").attr("value"));
        $("#period-month-3").val($("#period-month-3 option[value=12]").attr("value"));
        
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

    $regressionMonth = $("#period-month-3").val();
    $regressionPrice = $("#price-dropdown-3").val();

    $.ajax({
        type: "POST",
        url: $url,
        data: {
               id1: global_bond_id1, id2: global_bond_id2, areaMonth: $areaMonth, areaPrice: $areaPrice, 
               historyMonth: $historyMonth, historyPrice: $historyPrice,
               regressionMonth: $regressionMonth, regressionPrice: $regressionPrice
              },
        success: function (result)
        {
            $('#AjaxLoaderDiv').fadeOut('slow');
            if (result.status == 1)
            {
                $(".main-bond-securities").html(result.main_title);
                global_security_title1 = result.global_security_title1;
                global_security_title2 = result.global_security_title2;        

                drawHistoryChart(result);
                drawAreaChart(result.data.area_chart);
                drawRegression(result.data.regression_chart);
                // drawRelvalChart(result.data.relval_chart);

                if(result.isEquity == 1)
                {
                    $("#price-dropdown-1 option[value=3]").hide();
                    $("#price-dropdown-2 option[value=3]").hide();
                    $("#price-dropdown-3 option[value=3]").hide();

                    if($("#price-dropdown-1").val() == 3)
                    {
                        $("#price-dropdown-1").val(1);                        
                    }

                    if($("#price-dropdown-2").val() == 3)
                    {
                        $("#price-dropdown-2").val(1);                        
                    }

                    if($("#price-dropdown-3").val() == 3)
                    {
                        $("#price-dropdown-3").val(1);                        
                    }
                }   
                else
                {
                    $("#price-dropdown-1 option[value=3]").show();
                    $("#price-dropdown-2 option[value=3]").show();
                    $("#price-dropdown-3 option[value=3]").show();                    
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


function drawRegression(data_values)
{
    var formatedData = [];
    var counter = data_values.length;
    var tmpValues = [];
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
        legend: {textStyle: {color: '#fff'}},
        hAxis: {title: '', titleTextStyle: {color: '#333'}},
        backgroundColor: {fill: 'transparent'},
        hAxis: 
        {
            textStyle: {color: '#fff'},
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
            textStyle: {color: '#fff'},
            gridlines: {color: "#39536b"},
            baselineColor: {color: "#39536b"},
        },
        colors: ['#fff', '#8ab3e2'],
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

function drawAreaChart(data_values) {
    var elementID = 'area_chart';

    var formatedData = [];
    var tmpValues = [];
    formatedData.push(["", ""]);
    if(data_values.length > 0)
    {
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
        hAxis: {title: '', titleTextStyle: {color: '#333'}},
        // vAxis: {minValue: 0},
        backgroundColor: {fill: 'transparent'},
        hAxis: 
        {
            textStyle: {color: '#fff'},
            gridlines: {color: "transparent",count: 12}
        },
        vAxis: 
        {
            textStyle: {color: '#fff'},
            gridlines: {color: "#39536b"},
            baselineColor: {color: "#39536b"},
            viewWindowMode:'explicit',
            viewWindow: 
            {
                min: $minVal,
                max: $maxVal       
            }            
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

function drawRelvalChart(data_values) 
{
    // console.log("values");
    // console.log(data_values);

    var elementID = "curve_chart23";
    var formatedData = [];

    var counter = 1;

    // alert(counter);

    // if(counter > 0)
    // {
    //     formatedData.push(["", ""]);
        
    //     for(var i in data_values)
    //     {
    //         var prices = [];

    //         for(j in data_values[i])
    //         {
    //             prices.push(data_values[i][j]);
    //         }  

    //         formatedData.push(i, prices);            
    //     }        
    // }   
    // else
    // {
    //     formatedData.push(["", ""]);
    //     formatedData.push(["", 0]);
    // } 

    // console.log(formatedData);

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

        // data.addRow([i, prices]);
        // formatedData.push(i, prices);            
    }        


    // data.addRows([
    //   [0, 67], [1, 88], [2, 77],
    //   [3, 93], [4, 85], [5, 91],
    //   [6, 71], [7, 78], [8, 93],
    //   [9, 80], [10, 82],[0, 75],
    //   [5, 80], [3, 90], [1, 72],
    //   [5, 75], [6, 68], [7, 98],
    //   [3, 82], [9, 94], [2, 79],
    //   [2, 95], [2, 86], [3, 67],
    //   [4, 60], [2, 80], [6, 92],
    //   [2, 81], [8, 79], [9, 83],
    //   [3, 75], [1, 80], [3, 71],
    //   [3, 89], [4, 92], [5, 85],
    //   [6, 92], [7, 78], [6, 95],
    //   [3, 81], [0, 64], [4, 85],
    //   [2, 83], [3, 96], [4, 77],
    //   [5, 89], [4, 89], [7, 84],
    //   [4, 92], [9, 98]
    // ]);

    // var data = google.visualization.arrayToDataTable(formatedData);

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

function drawHistoryChart(data_values)
{
    var tmpValues = [];
    var tmpValues2 = [];    
    result = data_values;
    data_values = result.data;

    global_security_title1 = result.global_security_title1;
    global_security_title2 = result.global_security_title2;        

    var formatedData = [];

    var counter = data_values.benchmark_history_data.length;


    $columnTitle = "";
 
    // console.log(data_values.benchmark_history_data);

    if (counter > 0)
    {
        $columnTitle = "";
        $columnTitle2 = "";
        var formatedData = [];

        formatedData.push(["", {label:$columnTitle, type:'number'}, {label: 'tooltip', role: 'tooltip', 'p': {'html': true}},{label: $columnTitle2, type:'number'},{label: 'tooltip', role: 'tooltip', 'p': {'html': true}}]);    
        for(var i in data_values.benchmark_history_data)
        {
           tmpValues.push(data_values.benchmark_history_data[i][1]);
           tmpValues2.push(data_values.benchmark_history_data[i][2]);

           var d = new Date(data_values.benchmark_history_data[i][3]);
           var $created = d;        

           var html1 = "<p style='white-space: nowrap;padding: 3px;'>"+global_security_title1  + "<br /> <b>" + data_values.benchmark_history_data[i][0] + ", " + data_values.benchmark_history_data[i][1] + "</b>"+"</p>";
           var html2 = "<p style='white-space: nowrap;padding: 3px;'>"+global_security_title2  + "<br /> <b>" + data_values.benchmark_history_data[i][0] + ", " + data_values.benchmark_history_data[i][2] + "</b>"+"</p>";

           formatedData.push
           (
                [
                    {v: $created, f: data_values.benchmark_history_data[i][0]},
                    data_values.benchmark_history_data[i][1], 
                    html1,
                    data_values.benchmark_history_data[i][2],
                    html2
                ]
           );        
           
           if(data_values.benchmark_history_data[i][1] < 0)
           {
                // alert(data_values.benchmark_history_data[i][1]);
           }            

           if(data_values.benchmark_history_data[i][2] < 0)
           {
                // alert(data_values.benchmark_history_data[i][2]);
           }            
        }   
    } 
    else
    {
        formatedData.push(["", ""]);
        formatedData.push(["", 0]);
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
        legend: {position: 'none'},
        backgroundColor: {fill: 'transparent'},
        axisTextStyle: {color: '#344b61'},
        titleTextStyle: {color: '#fff'},
        legendTextStyle: {color: '#ccc'},
        vAxes: 
        {
            0:
            {
                viewWindowMode:'explicit',
                viewWindow: 
                {
                    min: $minVal,
                    max: $maxVal,       
                    minValue: $minVal,
                    maxValue: $maxVal,       
                }                                                        
            },
            1:
            {
                gridlines: {color: "transparent"},
                viewWindowMode:'explicit',
                viewWindow: 
                {
                    min: $minVal2,
                    max: $maxVal2,
                    minValue: $minVal2,
                    maxValue: $maxVal2,                           
                }                                                        
            }
        },        
        series: 
        {
          0: {targetAxisIndex: 0},
          1: {targetAxisIndex: 1}
        },        
        colors: ['white'],
        hAxis: {
            textStyle: {color: '#fff'},
            gridlines: {color: "transparent",count: 12}
        },
        vAxis: 
        {
            textStyle: {color: '#fff'},
            gridlines: {color: "#39536b"},
            baselineColor: {color: "#39536b"},
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

    var chart = new google.visualization.LineChart(document.getElementById('curve_chart23'));
    chart.draw(data, options);
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
               id1: global_bond_id1, 
               id2: global_bond_id2,
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


$(window).load(function(){
   if($.trim(global_page_type) != '') 
   navigateToDiv($.trim(global_page_type)); 
});

$(document).ready(function () {
    $('.top_bg').parallax({
        imageSrc: '/themes/frontend/images/economics-bg.jpg'
    });

    $(document).on("change","#period-month-1",function(){
        reInitChart();
    });

    $(document).on("change","#price-dropdown-1",function(){
        reInitChart();
    });    

    $(document).on("change","#period-month-2",function(){
        reInitChart();
    });

    $(document).on("change","#price-dropdown-2",function(){
        reInitChart();
    });

    $(document).on("change","#period-month-3",function(){
        reInitChart();
    });

    $(document).on("change","#price-dropdown-3",function(){
        reInitChart();
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

        $("#price-dropdown-4").val(3);

        if($(this).val() == 1)
        {
            $("#price-dropdown-4 option[value=3]").text("P/E Ratio");
            // $("#price-dropdown-4 option[value=1]").hide();                        
        }
        else
        {
            $("#price-dropdown-4 option[value=3]").text("Spread");
            // $("#price-dropdown-4 option[value=1]").show();
        }

        initRelvalChart();
        $(".rel-val-sub-title").text($("#relvalCreditEquity option:selected").text())
    });        
});
