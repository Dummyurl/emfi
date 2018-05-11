@extends('emfi_layout')
@section('content')
<section class="services">
  <div class="">
    <div class="item">
      <?php 
		$image = asset('themes/frontend/images/home-bg-1.jpg');	
	  ?>
      <div class="">
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
  </div>
  <!-- owl-carousel end -->
</section>
<section class="office_details_map">
    <div class="container" id="locations">
        <div class="row">
            <div class="col-md-4">
                <div class="office_details">
                    <h3>{{ __('about.london') }}</h3>
                    <address>
                        <strong>EMFI</strong> SECURITIES<br>
                        32 Devonshire Pl<br>
                        London, W1G 6JL<br>
                        United Kingdom
                    </address>
                    <a href="mailto:lndon@emfisecurities.com">lndon@emfisecurities.com</a>
                </div>
            </div>
            <div class="col-md-8">
                <div class="office_map map_mrgn_l">
                    <div id="map" class="map"></div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop
@section('scripts')
<script src="{{ asset('themes/frontend/js/home.js') }}"></script>
<script src="{{ asset('themes/emfi/js/location_map.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA4se7HxOqOpUPcelVjD7Odc_BBP4qdqHE&libraries=places&callback=init2"></script>
@stop 
