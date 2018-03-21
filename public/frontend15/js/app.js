var app = angular.module('graphs', []).controller('graphs-controller',  function($scope,$http,$compile){
	

    $scope.period=['1', '2' , '3', '6','12'];
    $scope.price=['z-spread'];
    $scope.duration=['maturity'];
    $scope.benchmark=['yield'];
    $scope.markets=['Currency','Commodity','Rates','Credits'];
   // $scope.market='Equity';
			$http.get('templates/Equity.html?'+random).then(function(response){ 
			$scope.template=response.data;
				var compiledeHTML = $compile($scope.template)($scope);
     		 $("#template").html(compiledeHTML);
     		 	// alert('loaded'); 
			
		 });
  
    var random=Math.random();
    $scope.periodchange=function(elementid){
	var i=$scope.periodic;
	//var data_values=[];
	$scope.elementid=elementid;
	// in place of this we can fire get request to read json file containing array .... 
	$http({url:"datafiles/jsondata"+$scope.periodic+".json?"+random,method:"GET"}).then(function(response){
		//console.log(response.data);
		$scope.data_hidden=response.data;
		var data_values=$scope.data_hidden;
         console.log($scope.elementid);
	 	google.charts.setOnLoadCallback(drawChartdynamic(data_values,$scope.elementid));
	});
	// reading values of $http i have done using hidden ng-model and storing in scope variable ..
	console.log(i);   
     
	};
	  function drawChartdynamic(data_values,elementid) {
        var data = google.visualization.arrayToDataTable(data_values);

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
		}
        };

        var chart = new google.visualization.LineChart(document.getElementById(elementid));

        chart.draw(data, options);
      }

      // bar graph starts ... 


      function drawBarChart(data_valuesb,elementidb) {
      //	alert(data_valuesb+'element:'+elementidb);
        var data = google.visualization.arrayToDataTable([
          ['Gainer', 'Points'],
          ['Gainer 5', 1100],
          ['Gainer 4', 870],
          ['Gainer 3', 560],
          ['Gainer 2', 1030],
		  ['Gainer 1', 730]
        ]);

        var options = {
          chart: {
            title: '',
            subtitle: '',
          },
          bars: 'horizontal', // Required for Material Bar Charts.
		  colors: ['white'],
		  backgroundColor: { fill:'transparent'},
		  legend: { position: 'none' },
		  hAxis: {
		      textStyle:{color: '#fff'},
			  gridlines: {color:"#39536b"},
			  baselineColor: '#39536b'
		  },
		vAxis: {
		    textStyle:{color: '#fff'},
			gridlines: {color:"#39536b"},
		}
        };
      
        var chart = new google.charts.Bar(document.getElementById(elementidb));
        chart.draw(data, google.charts.Bar.convertOptions(options));
        
       
      
      }

           function drawBarChart2(data_valuesb2,elementidb2) {
        var data = google.visualization.arrayToDataTable([
          ['Loser', 'Points'],
          ['Loser 5', 910],
          ['Loser 4', 510],
          ['Loser 3', 670],
          ['Loser 2', 830],
		  ['Loser 1', 320]
        ]);

        var options = {
          chart: {
            title: '',
            subtitle: '',
          },
          bars: 'horizontal', // Required for Material Bar Charts.
		  colors: ['white'],
		  backgroundColor: { fill:'transparent'},
		  legend: { position: 'none' },
		  hAxis: {
		      textStyle:{color: '#fff'},
			  gridlines: {color:"#39536b"},
			  baselineColor: '#39536b'
		  },
		vAxis: {
		    textStyle:{color: '#fff'},
			gridlines: {color:"#39536b"},
			}
		};

        var chart = new google.charts.Bar(document.getElementById(elementidb2));
        chart.draw(data, google.charts.Bar.convertOptions(options));
      }

      // bar graph ends ..
	
	// if($scope.market==''){ //by default load equity data... 
	// 	$scope.market='Equity';
	// 		$http.get('templates/Equity.html?'+random).then(function(response){ 
	// 		$scope.template=response.data;
	// 			var compiledeHTML = $compile($scope.template)($scope);
 //     		 $("#template").html(compiledeHTML);
     		 	  
			
	// 	 });

	// 	} //equity data loading ends...
	$scope.marketselect= function(){
		$scope.template=[];
		if($scope.market==''){
			$http.get('templates/Equity.html?'+random).then(function(response){ 
			$scope.template=response.data;
			//$("#template").html($scope.template);
			var compiledeHTML = $compile($scope.template)($scope);
     		 $("#template").html(compiledeHTML);
     		 var data_values=[["Month","index"],["jan",500],["jul",200]];
     		  google.charts.setOnLoadCallback(drawChartdynamic(data_values,'curve_chart_Equity'));

     		   // bar graph ... 
     			 var data_valuesb=[         ['Gainer', 'points'],
          ['Gainer 5', 110],
          ['Gainer 4', 87],
          ['Gainer 3', 56],
          ['Gainer 2', 103],
		  ['Gainer 1', 73]
        ];

     			 google.charts.setOnLoadCallback(drawBarChart(data_valuesb,'bar_chart_Equity'));
 				 // end of bar graph ... 
 				 // start of bar graph2 ... 
     			  var data_valuesb2=[         ['Gainer', 'points'],
          ['Gainer 5', 100],
          ['Gainer 4', 70],
          ['Gainer 3', 60],
          ['Gainer 2', 30],
		  ['Gainer 1', 30]
        ];
         
     			 google.charts.setOnLoadCallback(drawBarChart2(data_valuesb2,'bar_chart2_Equity'));
     			 // end of bar graph2 ... 
			
		 });

		}else{ 
				$http.get('templates/'+$scope.market+'.html?'+random).then(function(response){ 
				$scope.template=response.data;
				
				var compiledeHTML = $compile($scope.template)($scope);
     			 $("#template").html(compiledeHTML);
     			 
     			 // curve chart 
     			 var data_values=[["Month","index"],["jan",500],["feb",1000],["mar",760],["apr",3000],["may",350],["jun",500],["jul",200],["aug",3000],["sep",1000],["oct",2500],["nov",350],["dec",5000]];
     			 google.charts.setOnLoadCallback(drawChartdynamic(data_values,'curve_chart_'+$scope.market));
     			 // end of curve chart ..
     			 // bar graph ... 
     			 var data_valuesb=[         ['Gainer', 'points'],
          ['Gainer 5', 1100],
          ['Gainer 4', 870],
          ['Gainer 3', 560],
          ['Gainer 2', 1030],
		  ['Gainer 1', 730]
        ];

     			 google.charts.setOnLoadCallback(drawBarChart(data_valuesb,'bar_chart_'+$scope.market));
 				 // end of bar graph ... 
 				 // start of bar graph2 ... 
     			  var data_valuesb2=[         ['Gainer', 'points'],
          ['Gainer 5', 1100],
          ['Gainer 4', 870],
          ['Gainer 3', 560],
          ['Gainer 2', 1030],
		  ['Gainer 1', 730]
        ];
         
     			 google.charts.setOnLoadCallback(drawBarChart2(data_valuesb2,'bar_chart2_'+$scope.market));
     			 // end of bar graph2 ... 
     			
				
		 });

		}
		
	};
	
});
