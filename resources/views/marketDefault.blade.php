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
                        <option value="" selected="">Select Market</option>
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
                    <a data-id="{{ $row['id'] }}" data-name="{{ ucwords(strtolower($row['market_name'])) }}" href="javascript:void(0);" class="view-btn custom-market-change">
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

<section class="chart_wrapper">
    <div class="container">
        <div class="title">
            <h2>{{ __('market.market_movers') }}</h2>
            <span class="market-chart-title"></span>
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
                <div id="curve_chart2" style="width: 100%; height: 480px"> </div>
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


<section class="chart_table grey_bg">
    <div class="container">
        <div class="title">
            <h2>{{ __('country.market_price') }}</h2>
            <span>
                Credit
            </span>
        </div>
    </div>
    <div class="container">
        <div class="table_wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Security</th>
                        <th>Bid</th>
                        <th>Ask</th>
                        <th>Yield</th>
                        <th>Spread</th>
                        <th>Change</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>


                            <a data-market="5" class="generate-bond-chart" href="javascript:void(0);" data-id="70" title="View Graph">
                                BRAZIL 8 7/8 10/14/19
                            </a>
                        </td>
                        <td>
                            109.25
                        </td>
                        <td>
                            109.75
                        </td>
                        <td>
                            2.33
                        </td>
                        <td>
                            0.00
                        </td>
                        <td>
                            -0.06
                        </td>
                    </tr>
                    <tr>
                        <td>


                            <a data-market="5" class="generate-bond-chart" href="javascript:void(0);" data-id="70" title="View Graph">
                                BRAZIL 12 3/4 01/15/20
                            </a>
                        </td>
                        <td>
                            117.40
                        </td>
                        <td>
                            117.90
                        </td>
                        <td>
                            2.32
                        </td>
                        <td>
                            0.00
                        </td>
                        <td>
                            -2.57
                        </td>
                    </tr>
                    <tr>
                        <td>


                            <a data-market="5" class="generate-bond-chart" href="javascript:void(0);" data-id="70" title="View Graph">
                                BRAZIL 4 7/8 01/22/21
                            </a>
                        </td>
                        <td>
                            103.85
                        </td>
                        <td>
                            104.35
                        </td>
                        <td>
                            3.31
                        </td>
                        <td>
                            56.00
                        </td>
                        <td>
                            -0.07
                        </td>
                    </tr>
                    <tr>
                        <td>


                            <a data-market="5" class="generate-bond-chart" href="javascript:void(0);" data-id="70" title="View Graph">
                                BRAZIL 2 5/8 01/05/23
                            </a>
                        </td>
                        <td>
                            94.65
                        </td>
                        <td>
                            95.15
                        </td>
                        <td>
                            3.82
                        </td>
                        <td>
                            101.00
                        </td>
                        <td>
                            -0.06
                        </td>
                    </tr>
                    <tr>
                        <td>


                            <a data-market="5" class="generate-bond-chart" href="javascript:void(0);" data-id="70" title="View Graph">
                                BRAZIL 8 7/8 04/15/24
                            </a>
                        </td>
                        <td>
                            125.65
                        </td>
                        <td>
                            126.15
                        </td>
                        <td>
                            3.97
                        </td>
                        <td>
                            115.00
                        </td>
                        <td>
                            0.04
                        </td>
                    </tr>
                    <tr>
                        <td>


                            <a data-market="5" class="generate-bond-chart" href="javascript:void(0);" data-id="70" title="View Graph">
                                BRAZIL 8 7/8 04/15/24
                            </a>
                        </td>
                        <td>
                            125.80
                        </td>
                        <td>
                            126.30
                        </td>
                        <td>
                            3.94
                        </td>
                        <td>
                            113.00
                        </td>
                        <td>
                            0.26
                        </td>
                    </tr>
                    <tr>
                        <td>


                            <a data-market="5" class="generate-bond-chart" href="javascript:void(0);" data-id="70" title="View Graph">
                                BRAZIL 4 1/4 01/07/25
                            </a>
                        </td>
                        <td>
                            99.45
                        </td>
                        <td>
                            99.95
                        </td>
                        <td>
                            4.30
                        </td>
                        <td>
                            147.00
                        </td>
                        <td>
                            0.10
                        </td>
                    </tr>
                    <tr>
                        <td>


                            <a data-market="5" class="generate-bond-chart" href="javascript:void(0);" data-id="70" title="View Graph">
                                BRAZIL 8 3/4 02/04/25
                            </a>
                        </td>
                        <td>
                            126.50
                        </td>
                        <td>
                            127.00
                        </td>
                        <td>
                            4.18
                        </td>
                        <td>
                            135.00
                        </td>
                        <td>
                            0.33
                        </td>
                    </tr>
                    <tr>
                        <td>


                            <a data-market="5" class="generate-bond-chart" href="javascript:void(0);" data-id="70" title="View Graph">
                                BRAZIL 6 04/07/26
                            </a>
                        </td>
                        <td>
                            109.45
                        </td>
                        <td>
                            109.95
                        </td>
                        <td>
                            4.54
                        </td>
                        <td>
                            170.00
                        </td>
                        <td>
                            0.23
                        </td>
                    </tr>
                    <tr>
                        <td>


                            <a data-market="5" class="generate-bond-chart" href="javascript:void(0);" data-id="70" title="View Graph">
                                BRAZIL 10 1/8 05/15/27
                            </a>
                        </td>
                        <td>
                            140.70
                        </td>
                        <td>
                            141.20
                        </td>
                        <td>
                            4.56
                        </td>
                        <td>
                            172.00
                        </td>
                        <td>
                            -0.22
                        </td>
                    </tr>
                    <tr>
                        <td>


                            <a data-market="5" class="generate-bond-chart b10" href="javascript:void(0);" data-id="70" title="View Graph">
                                BRAZIL 4 5/8 01/13/28
                            </a>
                        </td>
                        <td>
                            97.50
                        </td>
                        <td>
                            98.00
                        </td>
                        <td>
                            4.92
                        </td>
                        <td>
                            206.00
                        </td>
                        <td>
                            0.15
                        </td>
                    </tr>
                    <tr>
                        <td>


                            <a data-market="5" class="generate-bond-chart" href="javascript:void(0);" data-id="70" title="View Graph">
                                BRAZIL 12 1/4 03/06/30
                            </a>
                        </td>
                        <td>
                            161.00
                        </td>
                        <td>
                            161.50
                        </td>
                        <td>
                            5.25
                        </td>
                        <td>
                            238.00
                        </td>
                        <td>
                            0.25
                        </td>
                    </tr>
                    <tr>
                        <td>


                            <a data-market="5" class="generate-bond-chart" href="javascript:void(0);" data-id="70" title="View Graph">
                                BRAZIL 8 1/4 01/20/34
                            </a>
                        </td>
                        <td>
                            126.20
                        </td>
                        <td>
                            126.70
                        </td>
                        <td>
                            5.68
                        </td>
                        <td>
                            279.00
                        </td>
                        <td>
                            0.00
                        </td>
                    </tr>
                    <tr>
                        <td>


                            <a data-market="5" class="generate-bond-chart" href="javascript:void(0);" data-id="70" title="View Graph">
                                BRAZIL 7 1/8 01/20/37
                            </a>
                        </td>
                        <td>
                            116.15
                        </td>
                        <td>
                            116.65
                        </td>
                        <td>
                            5.69
                        </td>
                        <td>
                            278.00
                        </td>
                        <td>
                            0.18
                        </td>
                    </tr>
                    <tr>
                        <td>


                            <a data-market="5" class="generate-bond-chart" href="javascript:void(0);" data-id="70" title="View Graph">
                                BRAZIL 5 5/8 01/07/41
                            </a>
                        </td>
                        <td>
                            97.25
                        </td>
                        <td>
                            97.75
                        </td>
                        <td>
                            5.82
                        </td>
                        <td>
                            292.00
                        </td>
                        <td>
                            0.24
                        </td>
                    </tr>
                    <tr>
                        <td>


                            <a data-market="5" class="generate-bond-chart" href="javascript:void(0);" data-id="70" title="View Graph">
                                BRAZIL 5 01/27/45
                            </a>
                        </td>
                        <td>
                            88.60
                        </td>
                        <td>
                            89.10
                        </td>
                        <td>
                            5.83
                        </td>
                        <td>
                            293.00
                        </td>
                        <td>
                            0.13
                        </td>
                    </tr>
                    <tr>
                        <td>


                            <a data-market="5" class="generate-bond-chart" href="javascript:void(0);" data-id="70" title="View Graph">
                                BRAZIL 5 5/8 02/21/47
                            </a>
                        </td>
                        <td>
                            96.35
                        </td>
                        <td>
                            96.85
                        </td>
                        <td>
                            5.87
                        </td>
                        <td>
                            298.00
                        </td>
                        <td>
                            0.25
                        </td>
                    </tr>
                </tbody>            </table>
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
                            <select id="price-dropdown" style="display: none;">
                                <option value="1" data-title="Price">Price</option>
                                <option value="2" data-title="Yield">Yield</option>
                                <option value="3" data-title="Spread" {!! ($selected_market == 5) ? 'selected="selected"':'' !!}>Spread</option>
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




@include('includes.twitter',['tweet_sub_title' => 'Latin America'])
@stop

@section('scripts')
<script src="{{ asset('themes/frontend/js/marketDefault.js') }}"></script>
<script type="text/javascript">

var treeObject, treeObject2, dataChart1, dataChart2, global_bond_id1, global_bond_id2;
var dataTemp;
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

function drawTreetChart(data_values, elementID) {

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
if (elementID == "treechart_div")
{
dataChart1 = google.visualization.arrayToDataTable(dataTemp);
treeObject = new google.visualization.TreeMap(document.getElementById(elementID));
treeObject.draw(dataChart1, {
// minColor: '#051b34',
// midColor: '#051b34',
// maxColor: '#051b34',
minColor: '#f00',
        midColor: '#0d0',
        maxColor: '#0d0',
        fontColor: 'white',
        minColorValue: 0,
        maxColorValue: 100,
        showScale: true,
        title: '',
        generateTooltip: showStaticTooltip
});
}
else if (elementID == "treechart_div2")
{
dataChart2 = google.visualization.arrayToDataTable(dataTemp);
treeObject2 = new google.visualization.TreeMap(document.getElementById(elementID));
treeObject2.draw(dataChart2, {
// minColor: '#051b34',
// midColor: '#051b34',
// maxColor: '#051b34',
minColor: '#f00',
        midColor: '#0d0',
        maxColor: '#0d0',
        minColorValue: 0,
        maxColorValue: 100,
        // headerHeight: 15,
        fontColor: 'white',
        showScale: true,
        title: '',
        generateTooltip: showStaticTooltip
});
}
}
</script>  
@stop
