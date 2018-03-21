@extends('layout')

@section('content')

<section class="top_section top_bg economics_bg">
  <div class="container">
    <div class="title_belt">
      <h2>Venezuela</h2>
      <span>February 14, 2018</span> </div>
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-6 four_block">
        <div class="inner_blue_box">
          <h3>Equities</h3>
          <span class="value">1200.12</span>
          <div class="botm clearfix">
            <div class="arrow"> <i class="up"><img src="{{ asset('themes/frontend/images/white-arrow-up.png') }}" alt=""></i> </div>
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
<section class="equities">
  <div class="container">
    <div class="title">
      <h2>Equities</h2>
      <span>Historical Chart</span> </div>
  </div>
  <div class="container chart_section">
    <div class="row">
      <div class="col-lg-12">
        <div id="curve_chart" style="width: 100%; height: 480px"> </div>
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
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="{{ asset('themes/frontend/js/economics.js') }}"></script>

@stop
