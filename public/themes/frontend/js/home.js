// google.charts.load('current', {packages: ['corechart', 'bar','']});
google.charts.load('current', {'packages': ['bar']});
google.charts.load('current', {'packages': ['corechart']});
google.charts.setOnLoadCallback(initChart);

function initChart()
{
	$(".charts-container").each(function(){
        $graph_type = $(this).data("type");
        $id = $(this).data("id");   
        $data = $("#chart-data-"+$id).html();
        $data = $.trim($data);
        $data = JSON.parse($data);
        $chartID = "chart-"+$id;
        if($graph_type == "market_movers_gainers")
        {
            drawGainerChart($chartID, $data);
        }
        else if($graph_type == "market_movers_laggers")
        {
            drawLoserChart($chartID, $data);
        }
        else if($graph_type == "market_history")
        {
            $option_banchmark = $(this).data("banchmark");
            $option_prices = $(this).data("prices");
            drawMarketHistoryChart($chartID, $data);
        }
        else if($graph_type == "yield_curve")
        {
            $option_banchmark = $(this).data("banchmark");
            $option_prices = $(this).data("prices");
            drawYieldCurveChart($chartID, $data);
        }
        else if($graph_type == "differential")
        {
            drawAreaChart($chartID, $data);
        }
        else if($graph_type == "regression")
        {
            drawRegression($chartID, $data);
        }
        else if($graph_type == "relative_value")
        {
            drawRelvalChart($chartID, $data);
        }
	});
}

function drawLoserChart(elementID, data_values)
{

    var formatedData = [];
    var counter = data_values.length;
    $columnTitle = "";
    if (counter > 0)
    {
        formatedData.push([$columnTitle, {label:'', type:'number'},{type: 'string', role: 'tooltip'}]);

        for (var i in data_values)
        {
            $per = parseFloat(data_values[i]['percentage_change']).toFixed(2);
            
            formatedData.push([data_values[i]['title'], $per, data_values[i]['title'] + ": "+$per+" %"]);
        }
    }
    else
    {
        formatedData.push(["", "",{type: 'string', role: 'tooltip'}]);
        formatedData.push(["None", 0,""]);
    }

    var data = google.visualization.arrayToDataTable(formatedData);
    var options = {
        chart: {
            title: '',
            subtitle: '',
        },
          bars: 'horizontal',
          legend: {position: 'none'},
          backgroundColor: { fill:'transparent'},
          axisTextStyle: { color: '#344b61' },
          titleTextStyle: { color: '#fff' },
          legendTextStyle: { color: '#ccc' },
          colors: ['white'],
          hAxis: {
              textStyle:{color: '#fff'},
              gridlines: {color:"#39536b"}
          },
        vAxis: {
            textStyle:{color: '#fff'},
            gridlines: {color:"#39536b"},
            baselineColor: {color:"#39536b"}
        }

    };

    var chart = new google.charts.Bar(document.getElementById(elementID));
    chart.draw(data, google.charts.Bar.convertOptions(options));
}

function drawGainerChart(elementID, data_values)
{

    var formatedData = [];
    var counter = data_values.length;
    $columnTitle = "";
    if (counter > 0)
    {
        formatedData.push([$columnTitle, {label:'', type:'number'},{type: 'string', role: 'tooltip'}]);

        for (var i in data_values)
        {
            $per = parseFloat(data_values[i]['percentage_change']).toFixed(2);
            
            formatedData.push([data_values[i]['title'], $per, data_values[i]['title'] + ": "+$per+" %"]);
        }
    }
    else
    {
        formatedData.push(["", "",{type: 'string', role: 'tooltip'}]);
        formatedData.push(["None", 0,""]);
    }

    var data = google.visualization.arrayToDataTable(formatedData);
    var options = {
        chart: {
            title: '',
            subtitle: '',
        },
        bars: 'horizontal',
          legend: {position: 'none'},
          backgroundColor: { fill:'transparent'},
          axisTextStyle: { color: '#344b61' },
          titleTextStyle: { color: '#fff' },
          legendTextStyle: { color: '#ccc' },
          colors: ['white'],
          hAxis: {
              textStyle:{color: '#fff'},
              gridlines: {color:"#39536b"}
          },
        vAxis: {
            textStyle:{color: '#fff'},
            gridlines: {color:"#39536b"},
            baselineColor: {color:"#39536b"}
        }

    };

    var chart = new google.charts.Bar(document.getElementById(elementID));
    chart.draw(data, google.charts.Bar.convertOptions(options));
}

function drawMarketHistoryChart(elementID, data_values)
{
    var formatedData = [];
    var option_prices       = data_values.options['option_prices'];
    var option_banchmark    = data_values.options['option_banchmark'];
    var counter = data_values.history_data.length;
    $columnTitle = "";
    if (counter > 0)
    {
        // formatedData.push([$columnTitle, $columnTitle]);
        formatedData.push([$columnTitle, $columnTitle,{label: 'tooltip', role: 'tooltip', 'p': {'html': true}}]);
        for (var i in data_values.history_data)
        {
            var d = new Date(data_values.history_data[i]['created']);
            var $created = d;
            if (option_prices == 2){
                 var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values.history_data[i]['title'] + "<br /> <b>" + data_values.history_data[i]['created_format'] + ", " + data_values.history_data[i]['YLD_YTM_MID'] + "</b>"+"</p>";
                 formatedData.push([{f: data_values.history_data[i]['created_format'], v:$created}, parseFloat(data_values.history_data[i]['YLD_YTM_MID']), html]);
            }else if (option_prices == 3){
                var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values.history_data[i]['title'] + "<br /> <b>" + data_values.history_data[i]['created_format'] + ", " + data_values.history_data[i]['Z_SPRD_MID'] + "</b>"+"</p>";
                formatedData.push([{f: data_values.history_data[i]['created_format'], v:$created}, parseFloat(data_values.history_data[i]['Z_SPRD_MID']), html]);
            } else {
                var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values.history_data[i]['title'] + "<br /> <b>" + data_values.history_data[i]['created_format'] + ", " + data_values.history_data[i]['last_price'] + "</b>"+"</p>";
                formatedData.push([{f: data_values.history_data[i]['created_format'], v:$created}, parseFloat(data_values.history_data[i]['last_price']), html]);
            }
        }
    } else {
        formatedData.push(["", ""]);
        formatedData.push(["", 0]);
    }

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
        colors: ['white'],
        hAxis: {
            textStyle: {color: '#fff'},
            // gridlines: {color: "#39536b"}
            gridlines: {color: "transparent",count: 12}
        },
        vAxis: {
            textStyle: {color: '#fff'},
            gridlines: {color: "#39536b"},
            baselineColor: {color: "#39536b"}
        }
    };
    var chart = new google.visualization.LineChart(document.getElementById(elementID));
    chart.draw(data, options);
    if(option_banchmark > 0){
        drawBenchMarkMarketHistoryChart(elementID, data_values, option_prices);
    }
}

function drawBenchMarkMarketHistoryChart(elementID, data_values, option_prices)
{
    $columnTitle = "";
    $columnTitle2 = "";
    var formatedData = [];
    var title    = data_values.options['title'];
    var title2    = data_values.options['title2'];
    // formatedData.push(["", {label:$columnTitle, type:'number'}, {label: $columnTitle2, type:'number'}]);
    formatedData.push(["", {label:$columnTitle, type:'number'}, {label: 'tooltip', role: 'tooltip', 'p': {'html': true}}, {label: $columnTitle2, type:'number'}, {label: 'tooltip', role: 'tooltip', 'p': {'html': true}}]);

    for(var i in data_values.benchmark_history_data)
    {
        var d = new Date(data_values.benchmark_history_data[i][3]);
        var $created = d;
        var html1 = "<p style='white-space: nowrap;padding: 3px;'>"+title + "<br /> <b>" + data_values.benchmark_history_data[i][0] + ", " + data_values.benchmark_history_data[i][1] + "</b>"+"</p>";
        var html2 = "<p style='white-space: nowrap;padding: 3px;'>"+title2 + "<br /> <b>" + data_values.benchmark_history_data[i][0] + ", " + data_values.benchmark_history_data[i][2] + "</b>"+"</p>";
        formatedData.push([{f: data_values.benchmark_history_data[i][0], v: $created},data_values.benchmark_history_data[i][1], html1, data_values.benchmark_history_data[i][2],html2]);

    }
    
    var data = google.visualization.arrayToDataTable(formatedData);
    var options = 
    {
        title: '',
        curveType: 'function',
        tooltip: {isHtml: true},
        legend: {position: 'none'},
        series: {
          0: {targetAxisIndex: 0},
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
        vAxis: {
            textStyle: {color: '#fff'},
            gridlines: {color: "#39536b"},
            baselineColor: {color: "#39536b"},
        }
    };
    var chart = new google.visualization.LineChart(document.getElementById(elementID));
    chart.draw(data, options);
}

function drawYieldCurveChart(elementID, data_values)
{
    var formatedData = [];
    var option_maturity     = data_values.options.option_maturity;
    var option_banchmark    = data_values.options.option_banchmark;
    var option_period       = data_values.options.option_period;
    var option_prices       = data_values.options.option_prices;

    var counter = data_values.history_data.length;
    $columnTitle = "";

    if (option_banchmark > 0)
    {
        drawYieldCurveBenchmarkChart(elementID, data_values);
    }
    else
    {

        if (counter > 0)
        {
            formatedData.push([{label:'', type:'number'}, $columnTitle,{label: 'tooltip', role: 'tooltip', 'p': {'html': true}}]);
            var j = 1;
            for (var i in data_values.history_data)
            {
                
                if(option_maturity == 1)
                {
                    var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values.history_data[i]['tooltip'] + "<br /> <b>" + data_values.history_data[i]['category'] + ", " + data_values.history_data[i]['price'] + "</b>"+"</p>";
                    formatedData.push([{v:parseFloat(data_values.history_data[i]['date_difference']), f:data_values.history_data[i]['category']}, parseFloat(data_values.history_data[i]['price']), html]);
                }else {
                    var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values.history_data[i]['tooltip'] + "<br /> <b>" + data_values.history_data[i]['category'] + ", " + data_values.history_data[i]['price'] + "</b>"+"</p>";
                    formatedData.push([data_values.history_data[i]['category'], parseFloat(data_values.history_data[i]['price']), html]);
                }

                j++;
            }
            
        }else
        {
            formatedData.push(["", ""]);
            formatedData.push(["", 0]);
        }

var data = google.visualization.arrayToDataTable(formatedData);
    var options = {
        curveType: 'function',
        tooltip: {isHtml: true},
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

    
}

function drawYieldCurveBenchmarkChart(elementID, data_values)
{
    // return false;

    $columnTitle = "";
    var formatedData = [];
    var option_maturity     = data_values.options.option_maturity;
    var option_banchmark    = data_values.options.option_banchmark;
    var option_period       = data_values.options.option_period;
    var option_prices       = data_values.options.option_prices;
    var price_text = GetPriceName(option_prices);
    formatedData.push([{label:'', type:'number'}, {label:'', type:'number'},{label: 'tooltip', role: 'tooltip', 'p': {'html': true}},{label: '', type:'number'},{label: 'tooltip', role: 'tooltip', 'p': {'html': true}}]);

    for(var i in data_values.benchmark_history_data)
    {
       if(option_maturity == 1)
       {
            // alert(data_values[i]['title1']);
            var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values.benchmark_history_data[i]['tooltip'] + "<br /> <b>" + data_values.benchmark_history_data[i]['title1'] + ", " + data_values.benchmark_history_data[i]['price1'] + "</b>"+"</p>";
            var html2 = "<p style='white-space: nowrap;padding: 3px;'>"+data_values.benchmark_history_data[i]['tooltip2'] + "<br /> <b>" + data_values.benchmark_history_data[i]['title2'] + ", " + data_values.benchmark_history_data[i]['price2'] + "</b>"+"</p>";
            formatedData.push([{v:parseFloat(data_values.benchmark_history_data[i]['date_difference']), f:data_values.benchmark_history_data[i]['title1']}, data_values.benchmark_history_data[i]['price1'], html, data_values.benchmark_history_data[i]['price2'],html2]);
       } else
       {
            var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values.benchmark_history_data[i]['tooltip'] + "<br /> <b>" + data_values.benchmark_history_data[i]['title1'] + ", " + data_values.benchmark_history_data[i]['price1'] + "</b>"+"</p>";
            var html2 = "<p style='white-space: nowrap;padding: 3px;'>"+data_values.benchmark_history_data[i]['tooltip2'] + "<br /> <b>" + data_values.benchmark_history_data[i]['title2'] + ", " + data_values.benchmark_history_data[i]['price2'] + "</b>"+"</p>";
            formatedData.push([data_values.benchmark_history_data[i]['title1'],data_values.benchmark_history_data[i]['price1'], html,data_values.benchmark_history_data[i]['price2'],html2]);
       }
    }
    var data = google.visualization.arrayToDataTable(formatedData);
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

function drawAreaChart(elementID, data_values)
{
    var formatedData = [];
    formatedData.push(["", ""]);
    if(data_values.area_chart.length > 0)
    {
        for(var i in data_values.area_chart)
        {
            // formatedData.push([data_values.area_chart[i]['created_format'], parseFloat(data_values.area_chart[i]['main_price'])]);
            var d = new Date(data_values.area_chart[i]['created']);
            var $created = d;
            formatedData.push([{f: data_values.area_chart[i]['created_format'], v:$created}, parseFloat(data_values.area_chart[i]['main_price'])]);        

        }
    }    
    else
    {
        formatedData.push(["", 0]);
    }
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
        },
        colors: ['#fff', '#8ab3e2'],
        auraColor: ['#11abc3', '#c7c3af'],
    };
    var chart = new google.visualization.AreaChart(document.getElementById(elementID));
    chart.draw(data, options);
}

function drawRegression(elementID, data_values)
{
    var formatedData = [];
    var counter = data_values.regression_chart.length;
    var price_text = GetPriceName(data_values.options.option_prices);
    if(counter > 0)
    {
        formatedData.push([
                {label:'', type:'number'}, 
                {label:price_text, type:'number'},
                {label: 'tooltip', role: 'tooltip', 'p': {'html': true}},
                {'type': 'string', 'role': 'style'}
                ]);
        var j = 1;
        for (var i in data_values.regression_chart)
        {
            var html = "<p style='white-space: nowrap;padding: 3px;'>"+data_values.regression_chart[i]['created_format'] + "<br /> <b>" + data_values.regression_chart[i]['main_price'] + ", " + data_values.regression_chart[i]['main_price2'] + "</b>"+"</p>";
            $style = null;
            var html ='';
            if(data_values.regression_chart[i]['is_recent'] == 1)
            {
                // $style = 'point {fill-color: #FF0000;zIndex: 99999;size: 18}';
                $style = 'point {fill-color: #FF0000;}';
            }

            formatedData.push(
                [
                    {v:parseFloat(data_values.regression_chart[i]['main_price']), f:data_values.regression_chart[i]['created_format']}, 
                    {v:parseFloat(data_values.regression_chart[i]['main_price2']), f:data_values.regression_chart[i]['created_format']},
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
              // showR2: true,
              // visibleInLegend: true
            }
        },
      };
    var chart = new google.visualization.ScatterChart(document.getElementById(elementID));
    chart.draw(data, options);
}

function drawRelvalChart(elementID, data_values)
{

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

    for(var i in data_values.relative_data)
    {
        var prices = [];
        var cnt = 0;
        prices.push(i);

        for(j in data_values.relative_data[i])
        {   
            prices.push(parseFloat(data_values.relative_data[i][j]['price']));
            var html = '<p style="white-space: nowrap;padding: 3px;"><b>'+data_values.relative_data[i][j]['country_title']+'</b><br />'+i+', '+parseFloat(data_values.relative_data[i][j]['price'])+'</p>';
            prices.push(html);
            var html = data_values.relative_data[i][j]['country_code'];
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
        annotations: {textStyle:{fontSize: 10}},
        tooltip: {isHtml: true},
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

function GetPriceName(prices_id){

    if(prices_id == 2)
    {
        return 'Yield';
    } else if (prices_id == 3)
    {
        return 'Spread';
    } else 
    {
        return 'Price';
    }
}

$(document).ready(function() {
    $('.top_bg').parallax({
      imageSrc: '/themes/frontend/images/economics-bg.jpg'
    });
});
