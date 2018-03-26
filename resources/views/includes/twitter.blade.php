<section class="twitter_updates">
    <div class="container">
        <div class="title">
            <h2>Updates</h2>
            <span>Latest Tweets</span> 
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
                        <img src="{{ asset('themes/frontend/images/twitter-post-img.png') }}" alt=""></div>
                    <div class="dec_col">
                        <div class="username">@emfisecurities</div>
                        <!-- <div class="subtxt">#Venezuela</div> -->
                        <p>{{ $tweet['comment'] }}</p>
                    </div>
                </div>
                <?php $i++; ?>
                @endforeach
            </div>
            @else
                <div class="col-md-12">
                    No Tweets Found !
                </div>    
            @endif
        </div>
    </div>
</section>
