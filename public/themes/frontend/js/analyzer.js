	google.load("visualization", "1", { packages: ["maps"] });
  	google.setOnLoadCallback(drawRegionsMap);

      function drawRegionsMap() {

      var data = google.visualization.arrayToDataTable([
       ['States'],
    //    ['California'],
      //  ['Texas'],
        ['New Mexico'],
      ]);

      var options = {
        region: 'US',
        resolution: 'provinces',
        colorAxis: {colors: ['#2677d3', '#2677d3']},
        backgroundColor: { fill:'transparent'},
        datalessRegionColor: '#ffffff',
        defaultColor: '#1d4878'
    };

      var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));
      chart.draw(data, options);
    }  

      google.load("visualization", "1", { packages: ["corechart"] });
      google.setOnLoadCallback(drawsScChart);
      function drawsScChart() {
        var data = google.visualization.arrayToDataTable([
          ['X', 'Points', 'Line'],
          [2, 3, 5],
          [4, 21.5, 20],
          [8, 29.8, null],
          [15.5, 33, 30],
          [20, 36, 35],
          [22, 44, 42],
          [35, 48, 50]
        ]);
        var options = {
          title: '',
          hAxis: { title: 'X', minValue: 0, maxValue: 60 },
          vAxis: { title: 'Y', minValue: 0, maxValue: 60 },
          legend: 'none',
          interpolateNulls: true,
          series: {
            1: { lineWidth: 1, pointSize: 0 }
          },
          backgroundColor: { fill:'transparent'},
          colors: ['#8ab3e2', '#fff'],
          hAxis: {
              textStyle:{color: '#fff'},
              //gridlines: {color:"#39536b"},
              gridlineColor: '#39536b',
              baselineColor: '#39536b',
//            baselineColor: {color:'#fff'}
          },
        vAxis: {
            textStyle:{color: '#fff'},
//          gridlines: {color:"#39536b"},
            baselineColor: '39536b',
            gridlineColor: '#39536b',
        }
            };
        var chart = new google.visualization.ScatterChart(document.getElementById('scatter_chart'));
        chart.draw(data, options);
      }

	google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart1);

      function drawChart1() {
        var data = google.visualization.arrayToDataTable([
          ['Day', 'line1', 'line2'],
          ['2004',  100, 21],
          ['2005',  570, 101],
          ['2006',  760, 1233],
          ['2007',  1210, 721],
          ['2008',  1350, 1100]
        ]);

        var options = {
          title: '',
          curveType: 'function',
          legend: { position: 'bottom' },
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
        colors: ['#fff', '#8ab3e2']
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
       google.charts.setOnLoadCallback(drawChart12);

      function drawChart12() {
        var data = google.visualization.arrayToDataTable([
          ['Day', 'data1', 'data2'],
          ['2004',  100, 321],
          ['2005',  570, 400],
          ['2006',  760, 800],
          ['2007',  1210, 600],
          ['2008',  1350, 1200]
        ]);

        var options = {
          title: '',
          curveType: 'function',
          legend: { position: 'bottom' },
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
        colors: ['#fff', '#8ab3e2']
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart2'));

        chart.draw(data, options);
      }
      google.charts.load('current', {'packages':['treemap']});
      google.charts.setOnLoadCallback(drawTreetChart);
      function drawTreetChart() {
        var data = google.visualization.arrayToDataTable([
          ['Location', 'Parent', 'Market trade volume (size)', 'Market increase/decrease (color)'],
          ['Global',    null,                 0,                               0],
          ['America',   'Global',             0,                               0],
          ['Europe',    'Global',             0,                               0],
          ['Asia',      'Global',             0,                               0],
          ['Australia', 'Global',             0,                               0],
          ['Africa',    'Global',             0,                               0],
          ['Antarctica', 'Global',          0,                                  0],

          ['Brazil',    'America',            11,                              10],
          ['USA',       'America',            52,                              31],
          ['Mexico',    'America',            24,                              12],
          ['Canada',    'America',            16,                              -23],
          ['Bouvet Island', 'Antarctica',             10,                       40],
          ['French Southern Territories','Antarctica',            20,           12],
          ['McDonald Islands','Antarctica',             30,                      2],
          ['South Georgia ','Antarctica',             60,                       22],
          ['Heard Island','Antarctica',             50,                         55],
          ['outh Sandwich Islands','Antarctica',             40,               32],
          ['France',    'Europe',             42,                              -11],
          ['Germany',   'Europe',             31,                              -2],
          ['Sweden',    'Europe',             22,                              -13],
          ['Italy',     'Europe',             17,                              4],
          ['UK',        'Europe',             21,                              -5],
          ['China',     'Asia',               36,                              4],
          ['Japan',     'Asia',               20,                              -12],
          ['India',     'Asia',               40,                              63],
          ['Laos',      'Asia',               4,                               34],
          ['Mongolia',  'Asia',               1,                               -5],
          ['Israel',    'Asia',               12,                              24],
          ['Iran',      'Asia',               18,                              13],
          ['Pakistan',  'Asia',               11,                              -52],
          ['Egypt',     'Africa',             21,                              0],
          ['S. Africa', 'Africa',             30,                              43],
          ['Sudan',     'Africa',             12,                              2],
          ['Congo',     'Africa',             10,                              12],
          ['Zaire',     'Africa',             8,                               10]
        ]);
        tree = new google.visualization.TreeMap(document.getElementById('treechart_div'));
        tree.draw(data, {
          minColor: '#051b34',
          midColor: '#051b34',
          maxColor: '#051b34',
          headerHeight: 15,
          fontColor: 'white',
          showScale: true
        });
      }

      google.charts.load('current', {'packages':['treemap']});
      google.charts.setOnLoadCallback(drawTreetChart2);
      function drawTreetChart2() {
        var data = google.visualization.arrayToDataTable([
          ['Location', 'Parent', 'Market trade volume (size)', 'Market increase/decrease (color)'],
          ['Global',    null,                 0,                               0],
          ['America',   'Global',             0,                               0],
          ['Europe',    'Global',             0,                               0],
          ['Asia',      'Global',             0,                               0],
          ['Australia', 'Global',             0,                               0],
          ['Africa',    'Global',             0,                               0],
          ['Antarctica', 'Global',          0,                                  0],

          ['Brazil',    'America',            11,                              10],
          ['USA',       'America',            52,                              31],
          ['Mexico',    'America',            24,                              12],
          ['Canada',    'America',            16,                              -23],
          ['Bouvet Island', 'Antarctica',             10,                       40],
          ['French Southern Territories','Antarctica',            20,           12],
          ['McDonald Islands','Antarctica',             30,                      2],
          ['South Georgia ','Antarctica',             60,                       22],
          ['Heard Island','Antarctica',             50,                         55],
          ['outh Sandwich Islands','Antarctica',             40,               32],
          ['France',    'Europe',             42,                              -11],
          ['Germany',   'Europe',             31,                              -2],
          ['Sweden',    'Europe',             22,                              -13],
          ['Italy',     'Europe',             17,                              4],
          ['UK',        'Europe',             21,                              -5],
          ['China',     'Asia',               36,                              4],
          ['Japan',     'Asia',               20,                              -12],
          ['India',     'Asia',               40,                              63],
          ['Laos',      'Asia',               4,                               34],
          ['Mongolia',  'Asia',               1,                               -5],
          ['Israel',    'Asia',               12,                              24],
          ['Iran',      'Asia',               18,                              13],
          ['Pakistan',  'Asia',               11,                              -52],
          ['Egypt',     'Africa',             21,                              0],
          ['S. Africa', 'Africa',             30,                              43],
          ['Sudan',     'Africa',             12,                              2],
          ['Congo',     'Africa',             10,                              12],
          ['Zaire',     'Africa',             8,                               10]
        ]);
        tree = new google.visualization.TreeMap(document.getElementById('treechart_div2'));
        tree.draw(data, {
          minColor: '#051b34',
          midColor: '#051b34',
          maxColor: '#051b34',
          headerHeight: 15,
          fontColor: 'white',
          showScale: true
        });

      }

      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Sales', 'Expenses'],
          ['2013',  1000,      400],
          ['2014',  1170,      460],
          ['2015',  660,       1120],
          ['2016',  1030,      540]
        ]);

        var options = {
          title: '',
          legend: 'none',
          hAxis: {title: 'Year',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0},
          backgroundColor: { fill:'transparent'},
          hAxis: {
              textStyle:{color: '#fff'},
              gridlines: {color:"#39536b"}
          },
        vAxis: {
            textStyle:{color: '#fff'},
            gridlines: {color:"#39536b"},
            baselineColor: {color:"#39536b"},
        },
        colors: ['#fff', '#8ab3e2'],
        auraColor: ['#11abc3', '#c7c3af'],
        };

        var chart = new google.visualization.AreaChart(document.getElementById('area_chart'));
        chart.draw(data, options);
      }

$(document).ready(function () {
    $('.top_bg').parallax({
        imageSrc: '/themes/frontend/images/economics-bg.jpg'
    });
});
