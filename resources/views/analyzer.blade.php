@extends('layout')

@section('content')
<style>
/*.google-visualization-tooltip-item {
  white-space: nowrap;
}    */
</style>
<section class="top_section top_bg economics_bg analyzer-page">
    <div class="container">
        <div class="title_belt">
            <h2>{{ __('analyzer.title') }}</h2>
            <span>{{ date('F d, Y',strtotime($last_update_date)) }}</span> 
        </div>
        <div class="treechart"></div>
        <div class="row">
            <div class="col-lg-6 col-md-6 treechart_block">
                <div class="inner_blue_box">
                    <div id="treechart_div" style="width: 100%; max-width: 900px; height: 450px;"></div>          
                </div>
            </div>
            <div class="col-lg-6 col-md-6 treechart_block">
                <div class="inner_blue_box">
                    <div id="treechart_div2" style="width: 100%; max-width: 900px; height: 450px;"></div>          
                </div>
            </div>
        </div>
    </div>
</section>
<div id="slope"></div>
<div id="bond-area" style="display: none;">
<section class="equities">
    <div class="container">
        <div class="title">
            <h2>{{ __('analyzer.market_history') }}</h2>
            <span class="main-bond-securities"></span>
        </div>
    </div>
    <div class="container chart_section">
        <div class="row">
            <div class="col-lg-12">
                <div id="curve_chart" style="width: 100%; height: 480px"> </div>
                <div class="chart_dropdown clearfix">
                    <form>
                        <div class="col-md-3">
                            <select id="period-month-1">                                
                                @foreach(getMonths() as $month => $label)
                                <option {!! 12 == $month ? 'selected="selected"':'' !!} value="{{ $month }}">
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>                            
                        </div>
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-3">
                            <select id="price-dropdown-1">
                                <option value="1" data-title="Price">Price</option>
                                <option value="2" data-title="Yield">Yield</option>
                                <option value="3" data-title="Spread" selected="selected">Spread</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="equities">
    <div class="container">
        <div class="title">
            <h2>{{ __('analyzer.differential') }}</h2>
            <span class="main-bond-securities"></span> 
        </div>
    </div>
    <div class="container chart_section">
        <div class="row">
            <div class="col-lg-12">
                <div id="area_chart" class="area_chart" style="width: 100%; height: 500px;"></div>
                <div class="chart_dropdown clearfix">
                    <form>
                        <div class="col-md-3">
                            <select id="period-month-2">                                
                                @foreach(getMonths() as $month => $label)
                                <option {!! 12 == $month ? 'selected="selected"':'' !!} value="{{ $month }}">
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>                            
                        </div>
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-3">
                            <select id="price-dropdown-2">
                                <option value="1" data-title="Price">Price</option>
                                <option value="2" data-title="Yield">Yield</option>
                                <option value="3" data-title="Spread" selected="selected">Spread</option>                              
                            </select>
                        </div>
                    </form>
                </div>                
            </div>
        </div>
    </div>
</section>

<div id="regression"></div>
<div class="clearfix"></div>
<section class="equities">
    <div class="container">
        <div class="title">
            <h2>{{ __('analyzer.regression') }}</h2>
            <span class="main-bond-securities"></span> 
        </div>
    </div>
    <div class="container chart_section">
        <div class="row">
            <div class="col-lg-12">
                <div id="scatter_chart" class="scatter_chart" style="width: 100%; height: 480px"> </div>
                <div class="chart_dropdown clearfix">
                    <form>
                        <div class="col-md-3">
                            <select id="period-month-3">                                
                                @foreach(getMonths() as $month => $label)
                                <option {!! 12 == $month ? 'selected="selected"':'' !!} value="{{ $month }}">
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>                            
                        </div>
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-3">
                            <select id="price-dropdown-3">
                                <option value="1" data-title="Price">Price</option>
                                <option value="2" data-title="Yield">Yield</option>
                                <option value="3" data-title="Spread" selected="selected">Spread</option>                              
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

</div>

<div id="relval" class="clearfix">&nbsp;</div>
<div class="clearfix"></div>
<section class="equities">
    <div class="container">
        <div class="title">
            <h2>{{ __('analyzer.relative_value') }}</h2>
            <span class="rel-val-sub-title">{{ __('analyzer.credit') }}</span>
        </div>
    </div>
    <div class="container chart_section">
        <div class="row">
            <div class="col-lg-12">
                <div id="curve_chart23" style="width: 100%; height: 480px"> </div>
                <div class="chart_dropdown clearfix">
                    <form>
                        <div class="col-md-3">
                            <select id="period-month-4">
                                <option selected value="{{ date('Y-m-d') }}">Today</option>   
                                @foreach(getMonths() as $month => $label)                               
                                @if($month == -1) 
                                    <option value="{{ date('Y-01-01') }}">
                                        {{ $label }}
                                    </option>                                
                                @else
                                    <option value="{{ date('Y-m-d', strtotime('-'.$month.' month')) }}">
                                        {{ $label }}
                                    </option>                                
                                @endif
                                @endforeach                                
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="relvalRating">                                
                                <option value="1">S&P Rating</option>
                                <option value="2">OECD Rating</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="price-dropdown-4">
                                <option value="1" data-title="Price">Price</option>
                                <option value="2" data-title="Yield">Yield</option>
                                <option value="3" data-title="Spread" selected="selected">Spread</option>                              
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="relvalCreditEquity">                                
                                <option value="5" selected="selected">Credit</option>
                                <option value="1">Equities</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@stop

@section('styles')
<style>
    svg[area-label='A chart.'] g g g text{font-size: 9px !important;}
</style>
@stop

@section('scripts')
<script type="text/javascript">
var global_page_type = '{{ $page_type }}';
var treeObject, treeObject2, dataChart1, dataChart2, global_bond_id1, global_bond_id2;
var default_security_id1 = 0; 
var default_security_id2 = 0;

@if($default_security_id1 > 0)
    default_security_id1 = {{ $default_security_id1 }};
@endif

@if($default_security_id2 > 0)
    default_security_id2 = {{ $default_security_id2 }};
@endif

// var default_security_id1 = 0; 
// var default_security_id2 = 0;


var row1 = 0;
var column1 = 0;

var row2 = 0;
var column2 = 0;
var dataTemp;

function showStaticTooltip(row, size, value) 
{   
    // console.log(row +" "+value);

    $mainTitle = dataChart1.getFormattedValue(row, 0); 
    $parent = dataChart1.getFormattedValue(row, 1);
    $per = dataChart1.getFormattedValue(row, 3);
    // if(typeof dataTemp[row][0]['f'] !== 'undefined')
    // {
    //     $mainTitle = dataTemp[row][0]['f'];
    // }    
    // else
    // {
    //     $mainTitle = dataTemp[row][0];
    // }

    if
    (
        $mainTitle != 'Country' && $mainTitle != 'Global' && $mainTitle != 'Equities' && $mainTitle != 'Credit' && 
        $parent != 'Country' && $parent != 'Global' && $parent != 'Equities' && $parent != 'Credit'
    )
    {
        return '<div style="background:#fd9; padding:10px; border-style:solid">'+$mainTitle+'<br />'+$per+'%</div>';        
    }
    else
    {
        return '<div style="background:#fd9; padding:10px; border-style:solid">'+$mainTitle+'</div>';
    }        
}


function drawTreetChart(data_values, elementID) {

    dataTemp =  
    [
        ['Country', 'Parent', 'Market trade volume (size)', 'Market increase/decrease (color)'],
        ['Global', null, 0, 0],
        ['Equities', 'Global', 0, 0],
        ['Credit','Global', 0, 0],        
        @foreach($equities['countries'] as $k=>$v)            
            @foreach($equities['countries'][$k]['records'] as $r)
            [{v: '{{ $r['id'] }} - Equities', f:'{{ $equities['countries'][$k]['title'] }}'},'Equities', 0, 0],
            [{v: '{{ $r['id'] }}', f:'{{ $r['security_name'] }}'},'{{ $r['id'] }} - Equities', {{ $r['data']['market_size'] }}, {{ $r['data']['percentage_change'] }}],
            @endforeach
        @endforeach
        @foreach($credits['countries'] as $k=>$v)
            [{v:'{{ $credits['countries'][$k]['title'] }} - Credit',f:'{{ $credits['countries'][$k]['title'] }}'},'Credit', 0, 0],
            @foreach($credits['countries'][$k]['records'] as $r)
            [{v: '{{ $r['id'] }}', f:'{{ $r['security_name'] }}'},'{{ $credits['countries'][$k]['title'] }} - Credit', {{ $r['data']['market_size'] }}, {{ $r['data']['percentage_change'] }}],
            @endforeach 
        @endforeach
    ];


    for(var i in dataTemp)
    {
        if (typeof dataTemp[i][3] !== 'undefined')
        {
            if(default_security_id1 > 0 && dataTemp[i][3] == default_security_id1)
            {
                row1 = i-1;
                column1 = 3;
            }   
            else if(default_security_id2 > 0 && dataTemp[i][3] == default_security_id2)
            {
                row2 = i-1;
                column2 = 3;                
            }            
        }    
    }        

    if(elementID == "treechart_div")
    {
        dataChart1 = google.visualization.arrayToDataTable(dataTemp);
        treeObject = new google.visualization.TreeMap(document.getElementById(elementID));

        treeObject.draw(dataChart1, {
            legend: {position: 'none'},
            // minColor: '#051b34',
            // midColor: '#051b34',
            // maxColor: '#051b34',
            legend: {position: 'none'},
            
//            minColor: '#f00',
//            midColor: '#4ae078',
//            maxColor: '#0d0',
            
//            minColor: '#f2f5f7',
//            midColor: '#fff',
            minColor: '#051b34',
            midColor: '#051b34',
            maxColor: '#051b34',
            
            fontColor: 'white',
            // minColorValue: 0,
            // maxColorValue: 100,
            showScale: false,
            title: '',
            generateTooltip: showStaticTooltip
        });
        
        google.visualization.events.addListener(treeObject, 'select', function () {

            var selection = treeObject.getSelection();
            var node_val = dataChart1.getValue(selection[0].row, 0);       
            var str = node_val;

            

            if(str.toLowerCase().indexOf("credit") >= 0)
            {
                node_val = 0;                
            }    
            else if(str.toLowerCase().indexOf("equities") >= 0)
            {                       
                str = str.replace(" - Equities", "");
                node_val = parseInt(str);
            }    
            else if(str.toLowerCase().indexOf("global") >= 0)
            {
                node_val = 0;
            }    

            // alert(node_val);

            if(node_val > 0)
            {
                global_bond_id1 =  node_val;
                generateSecurityBasedChart(1);
            }
            else
            {
                global_bond_id1 =  0;
                generateSecurityBasedChart(1); 
            }            
        });    

        if(default_security_id1 > 0 && row1 > 0 && column1 > 0) 
        {
            treeObject.setSelection([{row:row1,column:column1}]);
            global_bond_id1 =  default_security_id1;
            generateSecurityBasedChart(0);            
        }    
        else
        {
            treeObject.setSelection([{row:1,column:0}]);
        }
    }
    else if(elementID == "treechart_div2")
    {
        dataChart2 = google.visualization.arrayToDataTable(dataTemp);
        treeObject2 = new google.visualization.TreeMap(document.getElementById(elementID));
        
        treeObject2.draw(dataChart2, {
            
            legend: {position: 'none'},
            // minColor: '#051b34',
            // midColor: '#051b34',
            // maxColor: '#051b34',
//            minColor: '#f00',
//            midColor: '#4ae078',
//            maxColor: '#0d0',            
//            minColor: '#f2f5f7',
//            midColor: '#fff',
            minColor: '#051b34',
            midColor: '#051b34',
            maxColor: '#051b34',            
            fontColor: 'white',
//            minColorValue: 0,
//            maxColorValue: 100,                     
            // headerHeight: 15,
            fontColor: 'white',
            showScale: false,
            title: '',
            generateTooltip: showStaticTooltip
        });
        
        google.visualization.events.addListener(treeObject2, 'select', function () {
            var selection = treeObject2.getSelection();
            var node_val = dataChart1.getValue(selection[0].row, 0);       
            var str = node_val;

            // alert(str)

            if(str.toLowerCase().indexOf("credit") >= 0)
            {
                node_val = 0;                
            }    
            else if(str.toLowerCase().indexOf("equities") >= 0)
            {
                str = str.replace(" - Equities", "");
                node_val = parseInt(str);                
            }    
            else if(str.toLowerCase().indexOf("global") >= 0)
            {
                node_val = 0;
            }    

            // alert(node_val);
            
            
            // alert(node_val);
            
            if(node_val > 0)
            {
                global_bond_id2 =  node_val;
                generateSecurityBasedChart(1);
            }
            else
            {
                global_bond_id2 =  0;
                generateSecurityBasedChart(1);                
            }
        });

        if(default_security_id2 > 0 && row2 > 0 && column2 > 0) 
        {
            treeObject2.setSelection([{row:row2,column:column2}]);
            global_bond_id2 =  default_security_id2;
            generateSecurityBasedChart(0);                        
        }            
        else
        {
            treeObject2.setSelection([{row:2,column:0}]);
        }        
    }
}
</script>
<script src="{{ asset('themes/frontend/js/analyzer.js') }}"></script>
@stop