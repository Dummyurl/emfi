google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(lineChart);

google.charts.load('current', {'packages': ['corechart']});

google.charts.load('current', {'packages': ['bar']});

$(document).ready(function (){
      generateHomeLineGraph();
});

function generateHomeLineGraph()
{
    var global_line_graph_id = 15;
    var val = 1;
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
                $columnTitle = "LineChart";
                // alert("Title: " + $columnTitle); 
                formatedData.push([$columnTitle, $columnTitle]);
                var j = 1;
                for (var i in data_values)
                {
                    formatedData.push([data_values[i]['created_format'], parseFloat(data_values[i]['last_price'])]);
                }
            }
            
            lineChart(formatedData);
        },
        error: function (error) {
            $('#AjaxLoaderDiv').fadeOut('slow');
            $.bootstrapGrowl("Internal server error !", {type: 'danger', delay: 4000});
        }
    });
}

function lineChart(data) {
  // var data = google.visualization.arrayToDataTable(data);
    /*var data = google.visualization.arrayToDataTable([
      ['Day', 'index'],
      ['2004',  100],
      ['2005',  570],
      ['2006',  760 ],
      ['2007',  1210],
      ['2008',  1350]
    ]);*/
  console.log(data);
    var options = {
      title: '',
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

    var chart = new google.visualization.LineChart(document.getElementById('chart_home'));
    chart.draw(data, options);

}

google.charts.setOnLoadCallback(drawChart2);
function drawChart2() {
    var data = google.visualization.arrayToDataTable([
      ['Day', 'index'],
      ['2004',  100],
      ['2005',  570],
      ['2006',  760 ],
      ['2007',  1210],
      ['2008',  1350]
    ]);

    var options = {
      title: '',
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

    var chart = new google.visualization.LineChart(document.getElementById('chart_home2'));

    chart.draw(data, options);
}

$(document).ready(function() {
    $('.top_bg').parallax({
      imageSrc: '/themes/frontend/images/economics-bg.jpg'
    });
});
