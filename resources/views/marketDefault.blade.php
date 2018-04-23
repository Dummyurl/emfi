@extends('layout')

@section('content')
<section class="top_section top_bg bg-2 market-page" id="main">
    <div class="container">
        <div class="title_belt">
            <div class="row">
                <div class="col-md-6">
                    <h2>{{ __('market.title') }}</h2>
                    <span>{{ date('F d, Y',strtotime($last_update_date)) }}</span>
                </div>
                <div class="col-md-6 select_r">
                    <select name="markets" id="markets">    
                        <option value="" selected="">Selector</option>
                        @foreach($markets as $val => $label)                        
                        <option data-url="{{ url(getMarketUrls($val)) }}" value="{{ $val }}">
                            {{ ucwords(strtolower($label)) }}
                        </option>
                        @endforeach                                                 
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            @if(!empty($market_boxes))
            @foreach($market_boxes as $row)            
            <div class="col-lg-3 col-md-3 col-sm-6 four_block">
                <div class="inner_blue_box">
                    <a data-id="{{ $row['id'] }}" data-name="{{ ucwords(strtolower($row['market_name'])) }}" href="javascript:void(0);" class="view-btn custom-market-change view-security-chart">
                        <span>{{ __('market.view_chart') }}</span>
                    </a>                  
                    <h3>{{ ucwords(strtolower($row['market_name'])) }}</h3>
                    <span class="value">
                        {{ $row['last_price'] }}
                    </span>
                    <div class="botm clearfix">
                        <div class="arrow">                             
                            <i class="up">
                                @if($row['arrow_up_down'] > 0)
                                <img src="{{ asset('themes/frontend/images/white-arrow-up.png') }}" alt="" />
                                @else
                                <img src="{{ asset('themes/frontend/images/white-arrow-down.png') }}" alt="" />
                                @endif 
                            </i>
                        </div>
                        <div class="value_num">
                            <p>
                                {{ $row['net_change'] > 0 ? "+":""}}{{ $row['net_change'] }}
                            </p>
                            <p>
                                {{ $row['percentage_change'] > 0 ? "+":""}}{{ $row['percentage_change'] }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @endforeach
            @endif
        </div>
    </div>
</section> 

<div class="treechart"></div>
<section class="full_chart_wrapper">
    <div id="treechart_div" style="width: 100%;height: 450px;"></div>          
</section>

<?php /*
<div class="row analyzer-page">
    <div class="col-lg-12 col-md-12 treechart_block">
        <div class="inner_blue_box">
            
        </div>
    </div>
</div>        
*/ ?>

<section class="chart_wrapper">
    <div class="container">
        <div class="title">
            <h2>{{ __('market.market_movers') }}</h2>
        </div>
    </div>
    <div class="container chart_section">
        <div class="row">
            <div class="col-lg-6">
                <div class="sub_title"><h3>{{ __('market.gainers') }}</h3></div>
                <div id="bar_chart" class="bar_chart" style="width: 100%; height: 400px"> </div>
            </div>
            <div class="col-lg-6">
                <div class="sub_title"><h3>{{ __('market.laggers') }}</h3></div>
                <div id="bar_chart2" class="bar_chart" style="width: 100%; height: 400px"> </div>
            </div>
        </div>
    </div>
</section>

<div class="clearfix"></div>

<section class="chart_table grey_bg">
    <div class="container">
        <div class="title">
            <h2>{{ __('country.market_price') }}</h2>
        </div>
    </div>
    <div class="container">
        <div class="table_wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Security</th>
                        <th>Last Price</th>
                        <th>Net Change</th>
                        <th>Change Percentage</th>                        
                    </tr>
                </thead>                
                <tbody>
                    @if(count($pricer_data) > 0)
                        @foreach($pricer_data as $row)
                            <tr>
                                <td>
                                    <a class="view-security-chart" href="javascript:void(0);" data-id="{{ $row['id'] }}" title="View Graph">
                                        {{ $row['security_name'] }}
                                    </a>                                    
                                </td>
                                <td>
                                    {{ $row['last_price'] }}
                                </td>
                                <td>
                                    {{ $row['net_change'] }}
                                </td>                                
                                <td>
                                    {{ $row['percentage_change'] }}%
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">
                                No Data Found !
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</section>                

<div id="linegraph-data"></div>
<section class="equities">
    <div class="container">
        <div class="title">
            <h2>{{ __('market.market_history') }}</h2>
            <span class="market-chart-title-security"></span>
        </div>
    </div>
    <div class="container chart_section">
        <div class="row">
            <div class="col-lg-12">
                <div id="curve_chart" style="width: 100%; height: 480px"> </div>
                <div class="chart_dropdown clearfix">
                    <form>
                        <div class="col-md-4">
                            <select id="period-month">                                
                                @foreach(getMonths() as $month => $label)
                                <option {!! 12 == $month ? 'selected="selected"':'' !!} value="{{ $month }}">
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="price-dropdown">
                                <option value="1" data-title="Price">Price</option>
                                <option value="2" data-title="Yield">Yield</option>
                                <option value="3" data-title="Spread">Spread</option>
                            </select>
                        </div>
                        <div class="col-md-4 pull-right">
                            <select id="benchmark-dropdown">
                                <option value="">Add Benchmark</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


<div style="display: none;" id="chart-data-gainer">{!! json_encode($gainer_data) !!}</div>
<div style="display: none;" id="chart-data-loser">{!! json_encode($loser_data) !!}</div>
@stop


@section('scripts')
<script src="{{ asset('themes/frontend/js/marketDefault.js') }}"></script>
<script type="text/javascript">
    var treeObject, treeObject2, dataChart1, dataChart2, global_bond_id1, global_bond_id2;
    var dataTemp;
    var top_gainer_data;
    var top_loser_data;
    
    function showStaticTooltip(row, size, value)
    {
        $mainTitle = dataChart1.getFormattedValue(row, 0);
        $parent = dataChart1.getFormattedValue(row, 1);
        $per = dataChart1.getFormattedValue(row, 3);
        if
        (
            $mainTitle != 'Country' && $mainTitle != 'Global' && $mainTitle != 'Equities' && $mainTitle != 'Credit' &&
            $parent != 'Country' && $parent != 'Global' && $parent != 'Equities' && $parent != 'Credit'
        )
        {
            return '<div style="background:#fd9; padding:10px; border-style:solid">' + $mainTitle + '<br />' + $per + '%</div>';
        }
        else
        {
            return '<div style="background:#fd9; padding:10px; border-style:solid">' + $mainTitle + '</div>';
        }
    }

    function drawTreetChart(data_values, elementID) 
    {

        dataTemp =
        [
                ['Country', 'Parent', 'Market trade volume (size)', 'Market increase/decrease (color)'],
                ['Global', null, 0, 0],
                ['Equities', 'Global', 0, 0],
                ['Credit', 'Global', 0, 0],
                @foreach($equities['countries'] as $k => $v)
                @foreach($equities['countries'][$k]['records'] as $r)
                    [{v: '{{ $r['id'] }} - Equities', f:'{{ $equities['countries'][$k]['title'] }}'}, 'Equities', 0, 0],
                    [{v: '{{ $r['id'] }}', f:'{{ $r['security_name'] }}'}, '{{ $r['id'] }} - Equities', {{ $r['data']['market_size'] }}, {{ $r['data']['percentage_change'] }}],
                @endforeach
                @endforeach
                @foreach($credits['countries'] as $k => $v)
                    [{v:'{{ $credits['countries'][$k]['title'] }} - Credit', f:'{{ $credits['countries'][$k]['title'] }}'}, 'Credit', 0, 0],
                @foreach($credits['countries'][$k]['records'] as $r)
                    [{v: '{{ $r['id'] }}', f:'{{ $r['security_name'] }}'}, '{{ $credits['countries'][$k]['title'] }} - Credit', {{ $r['data']['market_size'] }}, {{ $r['data']['percentage_change'] }}],
                @endforeach
                @endforeach
                ];
                if(elementID == "treechart_div")
                {
                        dataChart1 = google.visualization.arrayToDataTable(dataTemp);
                        treeObject = new google.visualization.TreeMap(document.getElementById(elementID));
                        treeObject.draw(dataChart1, {
                                legend: {position: 'none'},
                                minColor: '#f00',
                                midColor: '#0d0',
                                maxColor: '#0d0',
                                fontColor: 'white',
                                minColorValue: 0,
                                maxColorValue: 100,
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

                            if(node_val > 0)
                            {
                                resetFields();
                                global_secure_id = node_val; 
                                loadMarketHistory();                                
                            }
                            else
                            {
                                global_bond_id1 =  0;
                            }            
                        });    
                        
                        treeObject.setSelection([{row:2,column: 0}]);
                }
    }
</script>  
@stop
