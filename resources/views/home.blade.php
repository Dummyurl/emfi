@extends('emfi_layout')

@section('content')
<section class="home_slider">
    <div class="owl-carousel owl-theme home_carousel">
        <div class="item">
            <div class="home_slider_item">
                <div class="container">
                    <div class="slider_inner">
                        <div class="title_belt">
                            <h2>Maxico</h2>
                            <span>February 14, 2018</span>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="chart_left"> <div id="chart_home" class="chart_home" style="width: 100%;"> </div> </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text_right">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pharetra eget metus eu volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean vitae sollicitudin justo. Donec nec pretium odio. Aliquam sed magna mi. Suspendisse bibendum, metus vel</p>
                                    <p>Integer sed tincidunt dui. Cras tincidunt at risus vitae ultrices. Sed at placerat diam. Nam ornare feugiat blandit. Suspendisse potenti. Nam et finibus elit. Integer ac turpis quam. Pellentesque eleifend ipsum a magna rhoncus, eu laoreet ipsum dapibus. Morbi pellentesque, nunc at pulvinar fringilla, nunc risus iaculis enim, finibus ornare turpis sem quis ex. Maecenas tristique varius orci vel eleifend. Sed tempor erat id mi tempor efficitur. Integer sed tincidunt dui.</p>
                                    <a href="#">Continue Reading <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div></div>
                </div>
            </div>
        </div>

        <div class="item">
            <div class="home_slider_item">
                <div class="container">
                    <div class="slider_inner">
                        <div class="title_belt">
                            <h2>Maxico</h2>
                            <span>February 14, 2018</span>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="chart_left"> <div id="chart_home2" class="chart_home" style="width: 100%;"> </div> </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text_right">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pharetra eget metus eu volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean vitae sollicitudin justo. Donec nec pretium odio. Aliquam sed magna mi. Suspendisse bibendum, metus vel</p>
                                    <p>Integer sed tincidunt dui. Cras tincidunt at risus vitae ultrices. Sed at placerat diam. Nam ornare feugiat blandit. Suspendisse potenti. Nam et finibus elit. Integer ac turpis quam. Pellentesque eleifend ipsum a magna rhoncus, eu laoreet ipsum dapibus. Morbi pellentesque, nunc at pulvinar fringilla, nunc risus iaculis enim, finibus ornare turpis sem quis ex. Maecenas tristique varius orci vel eleifend. Sed tempor erat id mi tempor efficitur. Integer sed tincidunt dui.</p>
                                    <a href="#">Continue Reading <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop

@section('scripts')
<script type="text/javascript">
    google.charts.load('current', {packages: ['corechart', 'line']});
    google.charts.setOnLoadCallback(lineChart);
    function lineChart() {
        var data = google.visualization.arrayToDataTable([
            ['Day', 'index', 'index2'],
            ['2004', 100, 250],
            ['2005', 570, 400],
            ['2006', 760, 500],
            ['2007', 1210, 1000],
            ['2008', 1350, 1200]
        ]);

        var options = {
            title: '',
            curveType: 'none',
            legend: {position: 'none'},
            backgroundColor: {fill: 'transparent'},
            axisTextStyle: {color: '#666666'},
            titleTextStyle: {color: '#fff'},
            legendTextStyle: {color: '#ccc'},
            colors: ['#051b34', '#666666'],
            hAxis: {
                textStyle: {color: '#666666'},
                gridlines: {color: "#39536b"}
            },
            vAxis: {
                textStyle: {color: '#666666'},
                gridlines: {color: "#ccc"},
                baselineColor: {color: "#ccc"}
            },
            chartArea: {left: 60, top: 60, right: 30, width: "100%", height: "72%"}
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_home'));

        chart.draw(data, options);
    }
    google.charts.setOnLoadCallback(drawChart2);
    function drawChart2() {
        var data = google.visualization.arrayToDataTable([
            ['Day', 'index', 'index2'],
            ['2004', 100, 250],
            ['2005', 570, 400],
            ['2006', 760, 500],
            ['2007', 1210, 1000],
            ['2008', 1350, 1200]
        ]);

        var options = {
            title: '',
            curveType: 'none',
            legend: {position: 'none'},
            backgroundColor: {fill: 'transparent'},
            axisTextStyle: {color: '#666666'},
            titleTextStyle: {color: '#fff'},
            legendTextStyle: {color: '#ccc'},
            colors: ['#051b34', '#666666'],
            hAxis: {
                textStyle: {color: '#666666'},
                gridlines: {color: "#ccc"}
            },
            vAxis: {
                textStyle: {color: '#666666'},
                gridlines: {color: "#ccc"},
                baselineColor: {color: "#ccc"}
            },
            chartArea: {left: 60, top: 60, right: 30, width: "100%", height: "72%"}
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_home2'));

        chart.draw(data, options);
    }
</script>
@stop
