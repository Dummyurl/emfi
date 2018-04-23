google.charts.load('current', {'packages': ['corechart','geochart']});
google.charts.setOnLoadCallback(initChart);

function initChart()
{
	$marketID = $("#markets").val();	
    $url = "/api/economics/get-country-data/" + $marketID;
    $('#AjaxLoaderDiv').fadeIn('slow');
    $.ajax({
        type: "POST",
        url: $url,
        success: function (result)
        {
            $('#AjaxLoaderDiv').fadeOut('slow');
            if (result.status == 1)
            {
				drawChart(result.data);            	
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

function drawChart(country_data)
{
    var mainData = country_data;
    
    country_data = mainData.countries;

    var colors_data = mainData.colors;
    var values_data = mainData.values;

   	var data = new google.visualization.DataTable();
   	data.addColumn('string', 'Country');
   	data.addColumn('number', "Daily Percentage Change");
    // data.addColumn({type: 'string', role: 'style'});    

	for(var i in country_data)
	{
		data.addRow
        (
            [
                {v: country_data[i].slug, f:country_data[i].title}, 
                {v: parseFloat(country_data[i].avg_percentage_change), f:parseFloat(country_data[i].avg_percentage_change)+"%"}                
            ]);
	}	

	var options = 
    {
        legend: 'none',
        region: '005',
        // backgroundColor: '#f2f5f7',
        backgroundColor: '#808080',
        width: '100%',
        // colorAxis: {colors: ['#f00', '#0d0']}
        colorAxis: 
        {
             values: values_data,
             colors: colors_data
        }    
    };

	var chart = new google.visualization.GeoChart(document.getElementById('geo-chart'));
	chart.draw(data, options);
    google.visualization.events.addListener(chart, 'select', function() {
        
        var selection = chart.getSelection();
        var category;
        
        category = '';
        
        for (var i = 0; i < selection.length; i++) 
        {
            var item = selection[i];
            category = data.getValue(chart.getSelection()[0].row, 0);
        }       

        if($.trim(category) != '')
        {
        	$('#AjaxLoaderDiv').fadeIn('slow');
        	window.location = '/economics/'+$.trim(category);
        }

    });        

}

$(document).ready(function() {
	
    $('.top_bg').parallax({
      imageSrc: '/themes/frontend/images/economics-bg.jpg'
    });

    $('select#markets').select2({
        allowClear: true,
        multiple: false,    
    });

    $(document).on("change", "select#markets", function(){
        initChart();
    });
});