@extends('emfi_layout')

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
                        @foreach($markets as $val => $label)
                        <option data-url="{{ url(getLangName().getMarketUrls($val)) }}" {{ $selected_market == $val ? 'selected="selected"':'' }} value="{{ $val }}">
                            {{ strtoupper($label) }}
                        </option>
                        @endforeach
                        <option data-url="{{ url(getLangName().'/developed') }}" value="DEVELOPED">DEVELOPED</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            @if(!empty($market_boxes))
            @foreach($market_boxes as $row)
            <div class="col-lg-3 col-md-3 col-sm-6 four_block">
                <div class="inner_blue_box">
                    <a data-id="{{ $row['id'] }}" data-name="{{ $row['market_name'] }}" href="javascript:void(0);" class="view-btn custom-market-change">
                        <span>{{ __('market.view_chart') }}</span>
                    </a>
                    <h3>{{ $row['market_name'] }}</h3>
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
            <?php /*
              <div class="col-lg-3 col-md-3 col-sm-6 four_block">
              <div class="inner_blue_box">
              <h3>Currency</h3>
              <span class="value">24.6789</span>
              <div class="botm clearfix">
              <div class="arrow"> <i class="up"><img src="{{ asset('themes/frontend/images/white-arrow-down.png') }}" alt=""></i> </div>
              <div class="value_num">
              <p>-0.8124</p>
              <p>-3.00%</p>
              </div>
              </div>
              </div>
              </div>
              <div class="col-lg-3 col-md-3 col-sm-6 four_block">
              <div class="inner_blue_box">
              <h3>Commodity</h3>
              <span class="value">100.84</span>
              <div class="botm clearfix">
              <div class="arrow"> <i class="up"><img src="{{ asset('themes/frontend/images/white-arrow-up.png') }}" alt=""></i> </div>
              <div class="value_num">
              <p>+2.34</p>
              <p>+0.85%</p>
              </div>
              </div>
              </div>
              </div>
              <div class="col-lg-3 col-md-3 col-sm-6 four_block">
              <div class="inner_blue_box">
              <h3>Rates</h3>
              <span class="value">1200.12</span>
              <div class="botm clearfix">
              <div class="arrow"> <i class="up"><img src="{{ asset('themes/frontend/images/white-arrow-down.png') }}" alt=""></i> </div>
              <div class="value_num">
              <p>-0.345</p>
              <p>-1.80%</p>
              </div>
              </div>
              </div>
              </div>
             */ ?>
        </div>
    </div>
</section>

<div class="treechart"></div>
<section class="full_chart_wrapper">
	<div class="container">
		<div id="treechart_div" style="width: 100%;height: 450px;"></div>
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

<div id="relval" class="clearfix"></div>
<div class="clearfix"></div>
<section class="equities">
    <div class="container">
        <div class="title">
            <h2>{{ __('analyzer.relative_value') }}</h2>
            <span class="rel-val-sub-title">
                @if($selected_market == 1)
                    {{ __('market.equities') }}
                @else
                    {{ __('market.credit') }}
                @endif
            </span>
        </div>
    </div>
    <div class="container chart_section">
        <div class="row">
            <div class="col-lg-12">
                <div id="curve_chart23" class="scatter_chart" style="width: 100%; height: 480px"> </div>
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

                        @if($selected_market == 1)
                            <div class="col-md-3">
                                <select id="price-dropdown-4">
                                    <!-- <option value="1" data-title="Price" selected="selected">Price</option> -->
                                    <option value="2" data-title="Yield">Yield</option>
                                    <option value="3" data-title="Spread" selected="selected">P/E Ratio</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="relvalCreditEquity">
                                    <option value="5">Credit</option>
                                    <option value="1" selected="selected">Equities</option>
                                </select>
                            </div>
                        @else
                            <div class="col-md-3">
                                <select id="price-dropdown-4">
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
                        @endif
                    </form>
                </div>
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
                        @if($selected_market == 5)
                            <th>{{ __('market.security') }}</th>
                            <th>{{ __('market.bid') }}</th>
                            <th>{{ __('market.ask') }}</th>
                            <th>{{ __('market.yield') }}</th>
                            <th>{{ __('market.spread') }}</th>
                            <th>{{ __('market.change') }}</th>
                        @else
                            <th>{{ __('market.security') }}</th>
                            <th>{{ __('market.last_price') }}</th>
                            <th>{{ __('market.net_change') }}</th>
                            <th>{{ __('market.change_percentage') }}</th>
                        @endif
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
                                @if($selected_market == 5)
                                    <td>
                                        {{ $row['bid_price'] }}
                                    </td>
                                    <td>
                                        {{ $row['ask_price'] }}
                                    </td>
                                    <td>
                                        {{ $row['yld_ytm_mid'] }}
                                    </td>
                                    <td>
                                        {{ $row['z_sprd_mid'] }}
                                    </td>
                                    <td>
                                        {{ $row['net_change'] }}
                                    </td>
                                @else
                                    <td>
                                        {{ $row['last_price'] }}
                                    </td>
                                    <td>
                                        {{ $row['net_change'] }}
                                    </td>
                                    <td>
                                        {{ $row['percentage_change'] }}%
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">
                                {{ __('market.no_data_found') }}
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
                <div id="curve_chart" class="curve_chart" style="width: 100%; height: 480px"> </div>
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
                                <option value="3" data-title="Spread" {!! ($selected_market == 5) ? 'selected="selected"':'' !!}>Spread</option>
                            </select>
                        </div>
                        <div class="col-md-4 pull-right">
                            <select id="benchmark-dropdown">
                                <option value="">{{ __('market.add_benchmark') }}</option>
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
<script type="text/javascript">
    var treeObject, treeObject2, dataChart1, dataChart2;
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
                    [{v: '{{ $r['id'] }}', f:'{{ $r['data']['title'] }}'}, '{{ $r['id'] }} - Equities', {{ $r['data']['market_size'] }}, {{ $r['data']['percentage_change'] }}],
                @endforeach
                @endforeach
                @foreach($credits['countries'] as $k => $v)
                    [{v:'{{ $credits['countries'][$k]['title'] }} - Credit', f:'{{ $credits['countries'][$k]['title'] }}'}, 'Credit', 0, 0],
                @foreach($credits['countries'][$k]['records'] as $r)
                    [{v: '{{ $r['id'] }}', f:'{{ $r['data']['title'] }}'}, '{{ $credits['countries'][$k]['title'] }} - Credit', {{ $r['data']['market_size'] }}, {{ $r['data']['percentage_change'] }}],
                @endforeach
                @endforeach
                ];
                if(elementID == "treechart_div")
                {
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
                                minColor: '#5c5959',
                                midColor: '#5c5959',
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

                            if(node_val > 0)
                            {
                                global_line_graph_text = dataChart1.getFormattedValue(selection[0].row, 0);
                                global_line_graph_id = node_val;
                                $("#benchmark-dropdown").html("");
                                generateLineGraph();
                                $('html, body').animate({
                                        scrollTop: $("#linegraph-data").offset().top - 50
                                }, 600);
                            }
                        });

                        @if($selected_market == 1)
                            treeObject.setSelection([{row:1,column: 0}]);
                        @else
                            treeObject.setSelection([{row:2,column: 0}]);
                        @endif
                }
    }
</script>
<script src="{{ asset('themes/emfi/js/market.js') }}"></script>
@stop
