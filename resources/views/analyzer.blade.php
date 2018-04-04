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
            <h2>Analyzer</h2>
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

<div id="bond-area" style="display: none;">
<section class="equities">
    <div class="container">
        <div class="title">
            <h2>History Chart</h2>
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
                                <option {!! 1 == $month ? 'selected="selected"':'' !!} value="{{ $month }}">
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
                                <option value="3" data-title="Spread">Spread</option>                              
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
            <h2>Security</h2>
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
                                <option {!! 1 == $month ? 'selected="selected"':'' !!} value="{{ $month }}">
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
                                <option value="3" data-title="Spread">Spread</option>                              
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
            <h2>Regression</h2>
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
                            <select name="">
                                <option selected>Period</option>
                                <option>Option 1</option>
                                <option>Option 2</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="">
                                <option selected>Price</option>
                                <option>Option 1</option>
                                <option>Option 2</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="">
                                <option selected>Duration</option>
                                <option>Option 1</option>
                                <option>Option 2</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="">
                                <option selected>Add Benchmark</option>
                                <option>Option 1</option>
                                <option>Option 2</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

</div>

<section class="equities">
    <div class="container">
        <div class="title">
            <h2>Security</h2>
            <span>Historical Chart</span>
        </div>
    </div>
    <div class="container chart_section">
        <div class="row">
            <div class="col-lg-12">
                <div id="curve_chart2" style="width: 100%; height: 480px"> </div>
                <div class="chart_dropdown clearfix">
                    <form>
                        <div class="col-md-3">
                            <select name="">
                                <option selected>Period</option>
                                <option>Option 1</option>
                                <option>Option 2</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="">
                                <option selected>Price</option>
                                <option>Option 1</option>
                                <option>Option 2</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="">
                                <option selected>Duration</option>
                                <option>Option 1</option>
                                <option>Option 2</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="">
                                <option selected>Add Benchmark</option>
                                <option>Option 1</option>
                                <option>Option 2</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
@section('scripts')
<script type="text/javascript">
var treeObject, treeObject2, dataChart1, dataChart2, global_bond_id1, global_bond_id2;
function showStaticTooltip(row, size, value) 
{
    return '<div style="background:#fd9; padding:10px; border-style:solid">Val: '+value+'</div>';
}

function drawTreetChart(data_values, elementID) {

    var dataTemp =  
    [
        ['Country', 'Parent', 'Market trade volume (size)', 'Market increase/decrease (color)'],
        ['Global', null, 0, 0],
        ['Equities', 'Global', 0, 0],
        ['Credit','Global', 0, 0],        
        @foreach($equities['countries'] as $k=>$v)
            [{v: '{{ $equities['countries'][$k]['title'] }} - Equities', f:'{{ $equities['countries'][$k]['title'] }}'},'Equities', 0, 0],
            @foreach($equities['countries'][$k]['records'] as $r)
            ['{{ $r['security_name'].' - '.$r['id'] }}','{{ $equities['countries'][$k]['title'] }} - Equities', {{ (int) $r['data']['market_size'] }}, {{ (int) $r['id'] }}],
            @endforeach
        @endforeach
        @foreach($credits['countries'] as $k=>$v)
            [{v:'{{ $credits['countries'][$k]['title'] }} - Credit',f:'{{ $credits['countries'][$k]['title'] }}'},'Credit', 0, 0],
            @foreach($credits['countries'][$k]['records'] as $r)
            [{v: '{{ $r['security_name'].' - '.$r['id'] }}', f:'{{ $r['security_name'] }}'},'{{ $credits['countries'][$k]['title'] }} - Credit', {{ (int) $r['data']['market_size'] }}, {{ (int) $r['id'] }}],
            @endforeach 
        @endforeach
    ];

    

    if(elementID == "treechart_div")
    {
        dataChart1 = google.visualization.arrayToDataTable(dataTemp);
        treeObject = new google.visualization.TreeMap(document.getElementById(elementID));
        
        treeObject.draw(dataChart1, {
            minColor: '#051b34',
            midColor: '#051b34',
            maxColor: '#051b34',
//            headerHeight: 15,
            fontColor: 'white',
            showScale: true,
            title: '',
            // generateTooltip: showStaticTooltip
        });
        
        google.visualization.events.addListener(treeObject, 'select', function () {
            var selection = treeObject.getSelection();
            var node_val = dataChart1.getValue(selection[0].row, 3);
            
            // alert(dataChart1.getValue(selection[0].row, 0))            
            // alert(node_val);
        
            if(node_val > 0)
            {
                global_bond_id1 =  node_val;
                generateSecurityBasedChart();
            }
            else
            {
                global_bond_id1 =  0;
                generateSecurityBasedChart(); 
            }            
        });    
    }
    else if(elementID == "treechart_div2")
    {
        dataChart2 = google.visualization.arrayToDataTable(dataTemp);
        treeObject2 = new google.visualization.TreeMap(document.getElementById(elementID));
        
        treeObject2.draw(dataChart2, {
            
            minColor: '#051b34',
            midColor: '#051b34',
            maxColor: '#051b34',
//            headerHeight: 15,
            fontColor: 'white',
            showScale: true,
            title: '',
            // generateTooltip: showStaticTooltip
        });
        
        google.visualization.events.addListener(treeObject2, 'select', function () {
            var selection = treeObject2.getSelection();
            var node_val = dataChart2.getValue(selection[0].row, 3);
            
            // alert(node_val);
            
            if(node_val > 0)
            {
                global_bond_id2 =  node_val;
                generateSecurityBasedChart();
            }
            else
            {
                global_bond_id2 =  0;
                generateSecurityBasedChart();                
            }
        });
    }
}
</script>
<script src="{{ asset('themes/frontend/js/analyzer.js') }}"></script>
@stop