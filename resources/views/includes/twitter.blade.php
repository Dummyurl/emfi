<section class="twitter_updates">
    <div class="container">
        <div class="title">
            <h2>{{ __('market.market_news') }}</h2>
            <span>{{ $tweet_sub_title or '' }}</span>
        </div>
        <div class="row">
            @if(!empty($tweets))
            <div class="col-md-12">
                @php
                $i = 1;
                @endphp
                @foreach($tweets as $tweet)
                <?php
                if ($i > 10)
                    break;
                ?>
                <div class="item">
                    <div class="post_date">
                        {{ $tweet['date'] }}
                    </div>
                    <div class="img_col">
                        <a href="https://twitter.com/EmfiSecurities" target="_blank">
                            <img src="{{ asset('themes/frontend/images/twitter-post-img.png') }}" alt="">
                        </a>
                    </div>
                    <div class="dec_col">
                        <div class="inner">
                            <div class="content_cell">
                                <div class="username">
                                    <!-- <a href="{{ $tweet['link'] }}" target="_blank">@emfisecurities</a> -->
                                </div>
                                <!-- <div class="subtxt">#Venezuela</div> -->
                                <p id="tweetText">{!! $tweet['comment'] !!}</p>
                            </div>
                        </div>
                    </div>



                    <div class="clearfix"></div>
                </div>
                <?php $i++; ?>
                @endforeach
            </div>
            @else
                <div class="col-md-12">
                    {{ __('market.no_tweets') }}
                </div>
            @endif
        </div>
    </div>
</section>
