@extends('emfi_layout')
@section('content')
<section class="home_slider services">
  <div class="owl-carousel owl-theme home_carousel">
    <div class="item">
      <?php 
		$image = asset('themes/frontend/images/home-bg-1.jpg');	
	  ?>
      <div class="home_slider_item">
        <div class="container">
          <div class="clearfix">
            <div class="title_belt">
              <h2> {{ $page_title }} </h2>
            </div>
          </div>
          <div class="clearfix">
            <div class="service_inner clearfix">
              <div class="col-md-6">
                <div class="service_img"><img src="http://demo.phpdots.com/themes/emfi/images/service-img.jpg" alt="EMFI Securities- services"></div>
              </div>
              <div class="col-md-6">
                <div class="text_right">
                  <h2>ELECTRONIC EXECUTION</h2>
                  <p>Through our partners, we provide direct market access to a 100 markets in 25 developed and emerging countries.<br>
                    <br>
                    We provide multi-asset execution for an array of products including stocks, options, futures, forex and bonds.<br>
                    <br>
                    Our seamless end-to-end process flows minimise human intervention and aid connectivity to market-leading third parties.</p>
                </div>
              </div>
            </div>
          </div>
          <!-- row end -->
        </div>
        <!-- container end -->
      </div>
      <!-- home_slider_item end -->
    </div>
    <!-- item end -->
    <div class="item">
      <?php 
		$image = asset('themes/frontend/images/home-bg-1.jpg');	
	  ?>
      <div class="home_slider_item">
        <div class="container">
          <div class="clearfix">
            <div class="title_belt">
              <h2> {{ $page_title }} </h2>
            </div>
          </div>
          <div class="clearfix">
            <div class="service_inner clearfix">
              <div class="col-md-6">
                <div class="service_img"><img src="http://demo.phpdots.com/themes/emfi/images/service-img.jpg" alt="EMFI Securities- services"></div>
              </div>
              <div class="col-md-6">
                <div class="text_right">
                  <h2>ELECTRONIC EXECUTION</h2>
                  <p>Through our partners, we provide direct market access to a 100 markets in 25 developed and emerging countries.<br>
                    <br>
                    We provide multi-asset execution for an array of products including stocks, options, futures, forex and bonds.<br>
                    <br>
                    Our seamless end-to-end process flows minimise human intervention and aid connectivity to market-leading third parties.</p>
                </div>
              </div>
            </div>
          </div>
          <!-- row end -->
        </div>
        <!-- container end -->
      </div>
      <!-- home_slider_item end -->
    </div>
    <!-- item end -->
  </div>
  <!-- owl-carousel end -->
</section>
@stop
@section('scripts')
<script src="{{ asset('themes/frontend/js/home.js') }}"></script>
@stop 