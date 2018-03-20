@extends('layout')

@section('content')
<section class="top_section top_bg bg-2" id="main">
    <div class="container">
        <div class="title_belt">
            <div class="row">
                <div class="col-md-6"><h2>Markets</h2></div>
                <div class="col-md-6 select_r">
                    <select name="markets">
                        <option value="" selected="selected">Equity</option>
                        <option value="Currency">Currency</option>
                        <option value="Commodity">Commodity</option>
                        <option value="Rates">Rates</option>
                        <option value="Credits">Credits</option>                                                 
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 four_block">
                <div class="inner_blue_box">
                    <h3>Equities</h3>
                    <span class="value">1200.12</span>
                    <div class="botm clearfix">
                        <div class="arrow"> <i class="up">
                                <img src="{{ asset('themes/frontend/images/white-arrow-up.png') }}" alt=""></i> </div>
                        <div class="value_num">
                            <p>+75</p>
                            <p>+1.00%</p>
                        </div>
                    </div>
                </div>
            </div>
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
        </div>
    </div>
</section> 

<div id="template"></div>

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
