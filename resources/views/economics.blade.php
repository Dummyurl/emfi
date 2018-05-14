@extends('emfi_layout')

@section('content')

@php
$twiceCountries = [];
$tickerIDs = [];
foreach($country_benchmarkes as $r)
{
    if(isset($twiceCountries[$r['country_id']]))
    {
        $twiceCountries[$r['country_id']] = $twiceCountries[$r['country_id']] + 1;
        $tickerIDs[] = $r['country_id'];
    }
    else
    {
        $twiceCountries[$r['country_id']] = 1;
    }    
}

@endphp

<section class="top_section top_bg economics_bg">
    <div class="container">
            
        <input type="hidden" id="default_benchmark_security_id" value="{{ $default_benchmark_security_id }}" />
        <input type="hidden" id="default_benchmark_security_title" value="{{ $default_benchmark_security_title }}" />

        <input type="hidden" id="main_country_id" value="{{ $countryObj->id }}" />
        <input type="hidden" id="main_lang" value="{{ getLangName() }}" />
        
        <div class="title_belt">
            <div class="row">
                <div class="col-md-6">
                  <h2>{{ __('country.title') }}</h2>
                  <span>{{ date('F d, Y',strtotime($last_update_date)) }}</span>
                </div>
                <div class="col-md-6 select_r">
                    <select id="country-combo">
                        @foreach($countries as $cnt)
                            <option {{ $cnt->id == $countryObj->id ? 'selected="selected"':'' }} value="{{ $cnt->slug }}">
                                {{ $cnt->country_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        
        <div class="row">
            @if(!empty($market_boxes))
                @foreach($market_boxes as $row)
                    
                    <div data-market="{{ $row['market_id'] }}" data-id="{{ $row['id'] }}" class="col-lg-3 col-md-3 col-sm-6 four_block market-action" style="cursor: pointer;" title="View Graph">
                        <div class="inner_blue_box">
                            <a class="view-btn">
                                <span>{{ __('country.view_chart') }}</span>
                            </a>                  
                            <h3>{{ $row['market_name'] or '' }}</h3>
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
    <div class="container">
        <div class="title">
            <h2>{{ __('country.market_map') }}</h2>
            <span class="market-chart-title" id="main-chart-title-1">
            </span> 
        </div>
        <div id="treechart_div" style="width: 100%;height: 450px;"></div>
    </div>
</section>


<div id="linegraph-data"></div>

<?php /*
<section class="equities">
    <div class="container">
        <div class="title">
            <h2 class="market-chart-title">Equities</h2>
            <span>Historical Chart</span> 
        </div>
    </div>
    <div class="container chart_section">
        <div class="row">
            <div class="col-lg-12">
                <div id="curve_chart" style="width: 100%; height: 480px"> </div>
                <div class="chart_dropdown clearfix">
                    <form>
                        <div class="col-md-3">
                            <select id="period-month">
                                <option selected value="{{ date('Y-m-d') }}">Today</option>   
                                @foreach(getMonths() as $month => $label)                                
                                <option value="{{ date('Y-m-d', strtotime('-'.$month.' month')) }}">
                                    {{ $label }}
                                </option>
                                @endforeach                                
                            </select>
                        </div>

                        
                        <div class="col-md-3">
                            <select id="duration-dropdown">
                                <option value="1">Maturity</option>
                                <option value="2">Duration</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select id="price-dropdown">
                                <option value="1" data-title="Price">PRICE</option>
                                <option value="2" data-title="Yield">YIELD</option>
                                <option value="3" data-title="Spread">SPREAD</option>
                            </select>
                        </div>
                        

                        <div class="col-md-3 pull-right">
                            <select id="benchmark-dropdown">
                                <option value="">Add Country</option>
                                @foreach($countries as $cnt)
                                    @if($cnt->id != $countryObj->id)
                                        <option value="{{ $cnt->id }}">{{ $cnt->title }}</option>
                                    @endif
                                @endforeach                                                                
                            </select>
                        </div>                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
*/ ?>

@if(!empty($bond_data))

@php
    $counter = 1;
@endphp

@foreach($bond_data as $key => $data)
@php
$tickerType = 0;
foreach($country_benchmarkes as $cnt)
{   
    if(trim(strtolower($key)) == trim(strtolower($cnt['ticker_name'])) && $cnt['country_id'] == $countryObj->id)
    {
        $tickerType = $cnt['ticker_type'];
    }
}
@endphp
<input type="hidden" id="ticker-type-{{ $counter}}" value="{{ $tickerType }}" />
<section class="equities">
    <div class="container">
        <div class="title">
            <h2>{{ __('country.yield_curve') }}</h2>
            <span class="market-chart-title" id="main-chart-title-{{ $counter}}">                
                @if(in_array($countryObj->id, $tickerIDs) && $tickerType == 2)
                    {{ strtoupper($key) }}
                @else
                    {{ $countryObj->country_name }}    
                @endif
            </span> 
            <div style="display: none;" id="hid-main-chart-title-{{ $counter}}">
                @if(in_array($countryObj->id, $tickerIDs) && $tickerType == 2)
                    {{ strtoupper($key) }}
                @else
                    {{ $countryObj->country_name }}
                @endif                
            </div>
        </div>
    </div>
    <div class="container chart_section">
        <div class="row">
            <div class="col-lg-12">
                <div id="curve_chart-{{ $counter }}" class="curve_chart" style="width: 100%; height: 480px"> </div>
                <div class="chart_dropdown clearfix">
                    <form>
                        <div class="col-md-3">
                            <select id="period-month-{{ $counter }}">
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
                            <select id="duration-dropdown-{{ $counter }}">
                                <option value="1">Maturity</option>
                                <option value="2" selected="selected">Duration</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select id="price-dropdown-{{ $counter }}">
                                <option value="1" data-title="Price">Price</option>
                                <option value="2" data-title="Yield">Yield</option>
                                <option value="3" data-title="Spread" selected="selected">Spread</option>                              
                            </select>
                        </div>
                        

                        <div class="col-md-3 pull-right">
                            <select id="benchmark-dropdown-{{ $counter }}">
                                <option value="">{{ __('market.add_benchmark') }}</option>
                                @foreach($country_benchmarkes as $cnt)
                                
                                    @if($cnt['country_id'] == $countryObj->id && $countryObj->id == 1)
                                        <?php continue; ?>
                                    @endif
                                
                                
                                    @if(trim(strtolower($key)) == trim(strtolower($cnt['ticker_name'])) && $cnt['country_id'] == $countryObj->id)
                                        
                                    @else
                                    <option data-tid="{{ $cnt['ticker_type'] }}" value="{{ $cnt['country_id'] }}">
                                        @if(in_array($cnt['country_id'],$tickerIDs) && $cnt['ticker_type'] == 2)
                                        {{ strtoupper($cnt['ticker_name']) }} 
                                        @else
                                        {{ $cnt['country_title'] }}
                                        @endif
                                    </option>    
                                    @endif
                                @endforeach                                                                
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
                @if(in_array($countryObj->id, $tickerIDs) && $tickerType == 2)
                    {{ strtoupper($key) }}
                @else
                    {{ ucwords(strtolower($countryObj->country_name)) }}    
                @endif                                
            </span>
        </div>
    </div>
    <div class="container">
        <div class="table_wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('market.security') }}</th>
                        <th>{{ __('market.bid') }}</th>
                        <th>{{ __('market.ask') }}</th>
                        <th>{{ __('market.yield') }}</th>
                        <th>{{ __('market.spread') }}</th>
                        <th>{{ __('market.change') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bond_data[$key] as $row)
                    <tr>
                        <td>

                            <?php 
                                $cssClassName = trim(strtolower($row['benchmark_family'])) == "b10" ? ' b10':'';
                            ?>

                            <a data-market="{{ $row['market_id'] }}" class="generate-bond-chart{{ $cssClassName }}" href="javascript:void(0);" data-id="{{ $row['id'] }}" title="View Graph">
                                {{ $row['security_name'] }}
                            </a>
                        </td>
                        <td>
                            {{ number_format($row['bid_price'],2) }}
                        </td>
                        <td>
                            {{ number_format($row['ask_price'],2) }}
                        </td>
                        <td>
                            {{ number_format($row['yld_ytm_mid'],2) }}
                        </td>
                        <td>
                            {{ number_format($row['z_sprd_mid'],2) }}
                        </td>
                        <td>
                            {{ number_format($row['net_change'],2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@php
    $counter++;
@endphp
@endforeach

@endif

<section class="equities" id="secondChartPart">
    <div class="container">
        <div class="title">
            <h2>{{ __('country.market_history') }}</h2>
            <span class="market-chart-title-2"></span> 
        </div>
    </div>
    <div class="container chart_section">
        <div class="row">
            <div class="col-lg-12">
                <div id="curve_chart2" class="curve_chart" style="width: 100%; height: 480px"> </div>
                <div class="chart_dropdown clearfix">
                    <form>
                        <div class="col-md-4">
                            <select id="period-month-10">                                
                                @foreach(getMonths() as $month => $label)
                                <option {!! $month == 12 ? 'selected="selected"':'' !!} value="{{ $month }}">
                                    {{ $label }}
                                </option>
                                @endforeach                                
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="price-dropdown-10" style="display: none;">
                                <option value="1" data-title="Price">Price</option>
                                <option value="2" data-title="Yield">Yield</option>
                                <option value="3" data-title="Spread">Spread</option>                              
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="benchmark-dropdown-10">
                                <option value="">{{ __('market.add_benchmark') }}</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="bond-area" style="display: none;">

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
                            <select id="period-month-11">                                
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
                            <select id="price-dropdown-11">
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
                            <select id="period-month-12">                                
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
                            <select id="price-dropdown-12">
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


@include('includes.twitter',['tweet_sub_title' => $countryObj->country_name])

@stop
@section('scripts')
<script src="{{ asset('themes/emfi/js/economics.js') }}"></script>
<script type="text/javascript">
    // alert("OK");
    var treeObject, treeObject2, dataChart1, dataChart2;
    var dataTemp;
    function drawTreetChart(elementID)
    {
        if($("#"+elementID).size() > 0)
        {
            dataTemp =
            [
                    ['Country', 'Parent', 'Market trade volume (size)', 'Market increase/decrease (color)'],
                    ['Global', null, 0, 0],
                    @foreach($treeMapData as $key => $r)
                        <?php $r = $treeMapData[$key]; ?>
                        [{v: '{{ $key }}', f:'{{ $r['security_name'] }}'}, 'Global', {{ $r['market_size'] }}, {{ $r['percentage_change'] }}],
                    @endforeach
            ];

            // console.log(dataTemp);

            dataChart1 = google.visualization.arrayToDataTable(dataTemp);
            treeObject = new google.visualization.TreeMap(document.getElementById(elementID));
            treeObject.draw(dataChart1, {
                    legend: {position: 'none'},

                    // minColor: '#f00',
                    // midColor: '#0d0',
                    // maxColor: '#0d0',

                    // minColor: '#ccc',
                    // midColor: '#051b34',
                    // maxColor: '#051b34',
                    minColor: '#ccc',
                    //midColor: '#5c5959',
                    maxColor: '#001a34',            
                    fontColor: 'white',
                    // minColorValue: 0,
                    // maxColorValue: 100,
                    showScale: false,
                    title: '',
                    headerHeight: 0,
                    // generateTooltip: showStaticTooltip
            });

            google.visualization.events.addListener(treeObject, 'select', function () {

                var selection = treeObject.getSelection();
                treeObject.setSelection([]);
                var node_val = dataChart1.getValue(selection[0].row, 0);
                var format_text = dataChart1.getFormattedValue(selection[0].row, 0);
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
                   global_secure_id_2 = node_val;
                   global_secure_id_2_text = format_text;
                   resetFields(3);
                   $("#price-dropdown-10").val(3);

                   $(".market-chart-title-2").html(global_secure_id_2_text);
                   generateLineGraph2();
                   
                   $('html, body').animate({
                            scrollTop: $("#secondChartPart").offset().top
                   }, 600);
                }
           });     

        }        
    }
</script>

@stop
