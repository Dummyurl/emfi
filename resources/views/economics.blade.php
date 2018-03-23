@extends('layout')

@section('content')

<section class="top_section top_bg economics_bg">
    <div class="container">
        <input type="hidden" id="main_country_id" value="{{ $countryObj->id }}" />
        <div class="title_belt">
            <h2>{{ $countryObj->title }}</h2>
            <span>{{ date('F d, Y') }}</span> 
        </div>
        <div class="row">
            @if(!empty($market_boxes))
                @foreach($market_boxes as $row)
                    @if(trim(strtolower($row['market_name'])) != 'credit')
                    <div data-id="{{ $row['id'] }}" class="col-lg-3 col-md-3 col-sm-6 four_block market-action" style="cursor: pointer;" title="View Graph">
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
        </div>
    </div>
</section>
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
                                <option value="">Add Benchmark</option>
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
            <h2>Table Chart</h2>
            <span>Chart</span> </div>
    </div>
    <div class="container">
        <div class="table_wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Sequrity</th>
                        <th>Bid</th>
                        <th>Ask</th>
                        <th>Yield</th>
                        <th>Spread</th>
                        <th>Change</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="">
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>0.178</td>
                    </tr>
                    <tr class="">
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>0.180</td>
                    </tr>
                    <tr class="">
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>0.178</td>
                    </tr>
                    <tr class="">
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>0.180</td>
                    </tr>
                    <tr class="">
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>0.178</td>
                    </tr>
                    <tr class="">
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>0.180</td>
                    </tr>
                    <tr class="">
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>0.178</td>
                    </tr>
                    <tr class="">
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>0.180</td>
                    </tr>
                    <tr class="">
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>0.178</td>
                    </tr>
                    <tr class="">
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>0.180</td>
                    </tr>
                    <tr class="">
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>0.178</td>
                    </tr>
                    <tr class="">
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>0.180</td>
                    </tr>
                    <tr class="">
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>113.735</td>
                        <td>0.178</td>
                    </tr>
                    <tr class="">
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>104.49</td>
                        <td>0.180</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
<section class="equities">
    <div class="container">
        <div class="title">
            <h2>Equities</h2>
            <span>Historical Chart</span> </div>
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
<section class="twitter_updates">
    <div class="container">
        <div class="title">
            <h2>Updates</h2>
            <span>Latest Tweets</span> 
        </div>
        <div class="row">
            <div class="col-md-12">
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
<script src="{{ asset('themes/frontend/js/economics.js') }}"></script>
@stop
