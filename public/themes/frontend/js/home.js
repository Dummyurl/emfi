google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(initChart);

function initChart()
{
	$(".charts-container").each(function(){
			var slider_id = $(this).data("id");
			var graph_period = $(this).data("period");
			var graphTitle = $(this).data("title");
            if($(this).data("type") == "line")
            {
                generateHomeLineGraph(slider_id, graph_period, graphTitle);
            }    			
            else
            {
                generateHomeYieldGraph(slider_id, graph_period, graphTitle, $(this));
            }
	});
}

function generateHomeYieldGraph(slider_id, graph_period, graphTitle, object)
{
    var global_line_graph_id = slider_id;
    var val = object.data("date");
    $url = "/api/economics/get-scatter-data";
    $('#AjaxLoaderDiv').fadeIn('slow');
    $.ajax({
        type: "POST",
        url: $url,
        data: {month_id: graph_period, benchmark_id: '', price_id: 1, duration: 1, country: object.data("country"), tid: '', current_ticker: 2},
        success: function (result)
        {
            data_values = result.data.history_data;
            elementID = "chart_home_"+slider_id;            
            var formatedData = [];

            var counter = data_values.length;

            $columnTitle = object.data("country_name")+" - Yield Curve";

            if (counter > 0)
            {
                formatedData.push(["", ""]);
                var j = 1;
                for (var i in data_values)
                {
                    formatedData.push([data_values[i]['category'], parseFloat(data_values[i]['price'])]);
                    j++;
                }
            } else
            {
                formatedData.push(["", ""]);
                formatedData.push(["", 0]);
            }

            var data = google.visualization.arrayToDataTable(formatedData);

            var options = {
                title: $columnTitle,
                curveType: 'function',
                legend: {position: 'bottom'},
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

        },
        error: function (error) {
            $('#AjaxLoaderDiv').fadeOut('slow');
            $.bootstrapGrowl("Internal server error !", {type: 'danger', delay: 4000});
        }
    });    
}

function generateHomeLineGraph(slider_id, graph_period, graphTitle)
{
    var global_line_graph_id = slider_id;
    var val = graph_period;
    $url = "/api/market/get-market-data/history";
    $('#AjaxLoaderDiv').fadeIn('slow');
    $.ajax({
        type: "POST",
        url: $url,
        data: {security_id: global_line_graph_id, month_id: val},
        success: function (result)
        {
            var formatedData = [];
            var data_values = result.data.history_data;
            // console.log(data_values);
            var counter = data_values.length;
            if (counter > 0)
            {
                $columnTitle = graphTitle;
                // alert("Title: " + $columnTitle);
                formatedData.push([$columnTitle, ""]);
                var j = 1;
                for (var i in data_values)
                {
                    formatedData.push([data_values[i]['created_format'], parseFloat(data_values[i]['last_price'])]);
                }
            }
			// console.log(formatedData);
            lineChart(formatedData, slider_id, $columnTitle);
        },
        error: function (error) {
            $('#AjaxLoaderDiv').fadeOut('slow');
            $.bootstrapGrowl("Internal server error !", {type: 'danger', delay: 4000});
        }
    });
}

function lineChart(formatedData, slider_id, title) {
    var data = google.visualization.arrayToDataTable(formatedData);
    var options = {
      	title: title,
      	curveType: 'none',
      	legend: { position: 'none' },
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
		},
chartArea:{left:60,top:60,right:30,width:"100%",height:"72%"}
    };
    var chart = new google.visualization.LineChart(document.getElementById('chart_home_'+ slider_id));
    chart.draw(data, options);

    $('#AjaxLoaderDiv').fadeOut('slow');
}

$(document).ready(function() {
    $('.top_bg').parallax({
      imageSrc: '/themes/frontend/images/economics-bg.jpg'
    });
});
