@extends('layout')

@section('content')

<section class="top_section top_bg economics_bg">
  <div class="container">
    <div class="title_belt">
      <h2>Analyer</h2>
      <span>February 14, 2018</span> </div>
    <div class="treechart">
    
    </div>
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
<section class="equities">
  <div class="container">
    <div class="title">
      <h2>Security</h2>
      <span>Historical Chart</span> </div>
  </div>
  <div class="container chart_section">
    <div class="row">
      <div class="col-lg-12">
        <div id="area_chart" class="area_chart" style="width: 100%; height: 500px;"></div>
      </div>
    </div>
  </div>
</section>
<section class="equities">
  <div class="container">
    <div class="title">
      <h2>Security</h2>
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
<section class="equities">
  <div class="container">
    <div class="title">
      <h2>Regression</h2>
      <span>Chart</span> </div>
  </div>
  <div class="container chart_section">
    <div class="row">
      <div class="col-lg-12">
        <div id="scatter_chart" class="scatter_chart" style="width: 100%; height: 480px"> </div>
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

<section class="equities">
  <div class="container">
    <div class="title">
      <h2>Security</h2>
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
          <div class="img_col"><img src="images/twitter-post-img.png" alt=""></div>
          <div class="dec_col">
            <div class="username">@emfisecurities</div>
            <div class="subtxt">#Venezuela</div>
            <p>Fusce aliquam tincidunt hendrerit. Nunc tincidunt id velit sit amet vestibulum. In venenatis tempus odio ut dictum. Curabitur ac nisl molestie, facilisis nibh ac, <a href="#">facilisis ligula</a>. Integer congue malesuada eros congue varius. Sed malesuada dolor eget velit</p>
          </div>
        </div>
        <div class="item">
          <div class="post_date">12 Jan</div>
          <div class="img_col"><img src="images/twitter-post-img.png" alt=""></div>
          <div class="dec_col">
            <div class="username">@emfisecurities</div>
            <div class="subtxt">#Venezuela</div>
            <p>Fusce aliquam tincidunt hendrerit. Nunc tincidunt id velit sit amet vestibulum. In venenatis tempus odio ut dictum. Curabitur ac nisl molestie, facilisis nibh ac, <a href="#">facilisis ligula</a>. Integer congue malesuada eros congue varius. Sed malesuada dolor eget velit</p>
          </div>
        </div>
        <div class="item">
          <div class="post_date">12 Jan</div>
          <div class="img_col"><img src="images/twitter-post-img.png" alt=""></div>
          <div class="dec_col">
            <div class="username">@emfisecurities</div>
            <div class="subtxt">#Venezuela</div>
            <p>Fusce aliquam tincidunt hendrerit. Nunc tincidunt id velit sit amet vestibulum. In venenatis tempus odio ut dictum. Curabitur ac nisl molestie, facilisis nibh ac, <a href="#">facilisis ligula</a>. Integer congue malesuada eros congue varius. Sed malesuada dolor eget velit</p>
          </div>
        </div>
        <div class="item">
          <div class="post_date">12 Jan</div>
          <div class="img_col"><img src="images/twitter-post-img.png" alt=""></div>
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
<script src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="{{ asset('themes/frontend/js/analyzer.js') }}"></script>
@stop