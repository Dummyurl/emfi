@extends('emfi_layout')
@section('content')
<?php /* <section class="services">
    <div class="item">
      <?php 
		$image = asset('themes/frontend/images/home-bg-1.jpg');	
	  ?>
        <div class="container">
            <div class="title_belt">
              <h2> {{ $page_title_name }} </h2>
            </div>
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
          <!-- service_inner end -->
        </div>
        <!-- container end -->
    </div>
</section>*/?>
<section class="home_slider">
    <div class="owl-carousel owl-theme home_carousel">
        <div class="item">
            <div class="home_slider_item">
                <div class="container">
                    <div class="slider_inner">
                        <div class="title_belt">
                          <h2>{{ $page_title_name }}</h2>
                          <span>{{ __('services.ass_sub_title') }}</span>
                        </div>
                        <div class="row">
                            <div class="slider_content">
                                <div class="col-md-6">
                                  <div class="chart_left"> 
                                    <div class="service_img"><img src="http://demo.phpdots.com/themes/emfi/images/service-img.jpg" alt="EMFI Securities- services"></div>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text_right">
                                        <h2>{{ __('services.our_services') }}</h2>
                                        <p>{{ __('services.ass_our_services_1') }}</p>
                                        <p>{{ __('services.ass_our_services_2') }}</p>
                                        <p>{{ __('services.ass_our_services_3') }}</p>
                                        <p>{{ __('services.ass_our_services_4') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="home_slider_item">
                <div class="container">
                    <div class="slider_inner">
                        <div class="title_belt">
                            <h2>{{ $page_title_name }}</h2>
                            <span>{{ __('services.ass_sub_title') }}</span>
                        </div>
                        <div class="row">
                            <div class="slider_content">
                                <div class="col-md-6">
                                  <div class="chart_left"> 
                                    <div class="service_img"><img src="http://demo.phpdots.com/themes/emfi/images/service-img.jpg" alt="EMFI Securities- services"></div>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text_right">
                                      <h2>{{ __('services.our_approach') }}</h2>
                                      <p>{{ __('services.ass_our_approach_1') }}</p>
                                      <p>{{ __('services.ass_our_approach_2') }}</p>
                                      <p>{{ __('services.ass_our_approach_3') }}</p>
                                      <p>{{ __('services.ass_our_approach_4') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="home_slider_item">
                <div class="container">
                    <div class="slider_inner">
                        <div class="title_belt">
                            <h2>{{ $page_title_name }}</h2>
                            <span>{{ __('services.ass_sub_title') }}</span>
                        </div>
                        <div class="row">
                            <div class="slider_content">
                                <div class="col-md-6">
                                  <div class="chart_left"> 
                                    <div class="service_img"><img src="http://demo.phpdots.com/themes/emfi/images/service-img.jpg" alt="EMFI Securities- services"></div>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text_right">
                                      <h2>{{ __('services.our_clients') }}</h2>
                                      <p>{{ __('services.ass_our_clients_1') }}</p>
                                      <p>{{ __('services.ass_our_clients_2') }}</p>
                                      <p>{{ __('services.ass_our_clients_3') }}</p>
                                      <p>{{ __('services.ass_our_clients_4') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="clearfix"></div>
<section class="office_details_map">
    <div class="container" id="locations">
        <div class="row">
            <div class="col-md-4">
                <div class="office_details">
                    <h3>NEW YORK</h3>
                    <address>
                        <strong>EMFI</strong> Capital LLC<br>
                        598 9th Ave<br>
                        New York, NY 10036<br>
                        United States
                    </address>
                    <a href="mailto:lndon@emfisecurities.com">capital@emfi.eu</a>
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA4se7HxOqOpUPcelVjD7Odc_BBP4qdqHE&libraries=places&callback=init3"></script>
@stop 