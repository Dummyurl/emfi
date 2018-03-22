@extends('layout')

@section('content')
<section class="top_section top_bg bg-2" id="main">
    <div class="container">
        <div class="title_belt">
            <div class="row">
                <div class="col-md-6">
                  <h2>Markets</h2>
                  <span>{{ date('d F, Y') }}</span>
                </div>
                <div class="col-md-6 select_r">
                    <select name="markets" id="markets">                        
                        @foreach($markets as $val => $label)
                        <option {{ $selected_market == $val ? 'selected="selected"':'' }} value="{{ $val }}">{{ $label }}</option>
                        @endforeach                                                 
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            @if(!empty($market_boxes))
            @foreach($market_boxes as $row)
            @if(trim(strtolower($row['market_name'])) != 'credit')
            <div class="col-lg-3 col-md-3 col-sm-6 four_block">
                <div class="inner_blue_box">
                    <h3>{{ $row['market_name'] or '' }}</h3>
                    <span class="value">
                        {{ number_format($row['last_price'],2)  }}
                    </span>
                    <div class="botm clearfix">
                        <div class="arrow">                             
                            <i class="up">
                                @if($row['net_change'] > 0)
                                <img src="{{ asset('themes/frontend/images/white-arrow-up.png') }}" alt="" />
                                @else
                                <img src="{{ asset('themes/frontend/images/white-arrow-down.png') }}" alt="" />
                                @endif 
                            </i>
                        </div>
                        <div class="value_num">
                            <p>
                                {{ $row['net_change'] > 0 ? "+":""}}{{ number_format($row['net_change'],2)  }}
                            </p>
                            <p>
                                {{ $row['percentage_change'] > 0 ? "+":""}}{{ number_format($row['percentage_change'],2)  }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif    
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
            <h2 class="market-chart-title">Equities</h2>
            <span>Top 5 Gainer &amp; Loser</span></div>
    </div>
    <div class="container chart_section">
        <div class="row">
            <div class="col-lg-6">
                <div class="sub_title"><h3>Top 5 Gainer</h3></div>
                <div id="bar_chart" class="bar_chart" style="width: 100%; height: 400px"> </div>
            </div>
            <div class="col-lg-6">
                <div class="sub_title"><h3>Top 5 Loser</h3></div>
                <div id="bar_chart2" class="bar_chart" style="width: 100%; height: 400px"> </div>
            </div>
        </div>
    </div>
</section>
<section class="equities">
    <div class="container">
        <div class="title">
            <h2 class="market-chart-title">Equities</h2>
            <span>Historical Chart</span> </div>
    </div>
    <div class="container chart_section">
        <div class="row">
            <div class="col-lg-12">
                <div id="curve_chart" style="width: 100%; height: 480px"> </div>
                <div class="chart_dropdown clearfix">
                    <form>
                        <div class="col-md-4">
                            <select id="period-month">
                                <option value="">Period</option>
                                @for($i=1;$i<=12;$i++)
                                <option {!! $i == 1 ? 'selected="selected"':'' !!} value="{{ $i }}">{{ $i }} Month</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="price-dropdown" style="display: none;">
                                <option value="1">Price</option>
                                <option value="2">YLD YTM MID</option>
                                <option value="3">Z SPRD MID</option>
                            </select>
                        </div>
                        <div class="col-md-4 pull-right">
                            <select id="benchmark-dropdown">
                                <option selected>Add Benchmark</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="twitter_updates">
    <div class="container">
        <div class="title">
            <h2>Updates</h2>
            <span>Latest Tweets</span> </div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="item">
                    <div class="post_date">12 Jan</div>
                    <div class="img_col"><img src="{{ asset('themes/frontend/images/twitter-post-img.png') }}" alt=""></div>
                    <div class="dec_col">
                        <div class="username">@emfisecurities</div>
                        <div class="subtxt">#Venezuela</div>
                        <p>Fusce aliquam tincidunt hendrerit. Nunc tincidunt id velit sit amet vestibulum. In venenatis tempus odio ut dictum. Curabitur ac nisl molestie, facilisis nibh ac, <a href="#">facilisis ligula</a>. Integer congue malesuada eros congue varius. Sed malesuada dolor eget velit</p>
                    </div>
                </div>
                <div class="item">
                    <div class="post_date">12 Jan</div>
                    <div class="img_col"><img src="{{ asset('themes/frontend/images/twitter-post-img.png') }}" alt=""></div>
                    <div class="dec_col">
                        <div class="username">@emfisecurities</div>
                        <div class="subtxt">#Venezuela</div>
                        <p>Fusce aliquam tincidunt hendrerit. Nunc tincidunt id velit sit amet vestibulum. In venenatis tempus odio ut dictum. Curabitur ac nisl molestie, facilisis nibh ac, <a href="#">facilisis ligula</a>. Integer congue malesuada eros congue varius. Sed malesuada dolor eget velit</p>
                    </div>
                </div>
                <div class="item">
                    <div class="post_date">12 Jan</div>
                    <div class="img_col"><img src="{{ asset('themes/frontend/images/twitter-post-img.png') }}" alt=""></div>
                    <div class="dec_col">
                        <div class="username">@emfisecurities</div>
                        <div class="subtxt">#Venezuela</div>
                        <p>Fusce aliquam tincidunt hendrerit. Nunc tincidunt id velit sit amet vestibulum. In venenatis tempus odio ut dictum. Curabitur ac nisl molestie, facilisis nibh ac, <a href="#">facilisis ligula</a>. Integer congue malesuada eros congue varius. Sed malesuada dolor eget velit</p>
                    </div>
                </div>
                <div class="item">
                    <div class="post_date">12 Jan</div>
                    <div class="img_col"><img src="{{ asset('themes/frontend/images/twitter-post-img.png') }}" alt=""></div>
                    <div class="dec_col">
                        <div class="username">@emfisecurities</div>
                        <div class="subtxt">#Venezuela</div>
                        <p>Fusce aliquam tincidunt hendrerit. Nunc tincidunt id velit sit amet vestibulum. In venenatis tempus odio ut dictum. Curabitur ac nisl molestie, facilisis nibh ac, <a href="#">facilisis ligula</a>. Integer congue malesuada eros congue varius. Sed malesuada dolor eget velit</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop

@section('scripts')
<script src="{{ asset('themes/frontend/js/market.js') }}"></script>
@stop
