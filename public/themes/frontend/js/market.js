google.charts.load('current', {'packages': ['corechart']});
google.charts.load('current', {'packages': ['bar']});


function drawBarChart(dataValues, elementID) {
    
    var data = google.visualization.arrayToDataTable(dataValues);

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

function drawChart()
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
    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
    chart.draw(data, options);
}

$(document).ready(function () {
    $('.bg-2').parallax({
        imageSrc: '/themes/frontend/images/bg-2.jpg'
    });
});