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
                        @foreach($markets as $val => $label)
                        <option data-url="{{ url(getMarketUrls($val)) }}" {{ $selected_market == $val ? 'selected="selected"':'' }} value="{{ $val }}">
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
<script src="{{ asset('themes/frontend/js/market.js') }}"></script>
@stop
