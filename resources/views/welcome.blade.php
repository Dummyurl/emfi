@extends('layout')
@section('content')
<section class="home_slider">
	@if(!empty($sliders))
    <div class="owl-carousel owl-theme home_carousel">
		@foreach($sliders as $slider)
		        <div class="item">
		        	<?php 
		        		 //echo "<pre>";
		        		 //print_r($slider);
		        		// print_r($slider->option_price);
		        		 //exit(); 
		        	?>
		            <div class="home_slider_item bgcover" style="background:url({{ asset('themes/frontend/images/home-bg-1.jpg') }})">
		                <div class="container">
		                    <div class="row">
								<div class="title_belt">
			                        <h2>@if(empty($slider->title) && $slider->title == '')
		                               	<?php 
		                               		$val = $slider->translate('en',true)->title;
		                               		if(empty($val))
		                               			$val = $slider->translate('es',true)->title;
		                               	?>
		                               		{{ $val }}
			                            @else
			                               		{!! $slider->title !!}
			                            @endif
			                        </h2>
			                        <span>{{ date('F d, Y',strtotime($last_update_date)) }}</span>
			                    </div>
			                    <div class="row">
			                        <div class="col-md-6">
			                            <div class="chart_left">
			                                <div class="charts-container" data-mainid="{{ $slider->id }}" data-id="{{ $slider->security_id }}"
			                                	data-date="{{ $slider->graph_period != -1 ? date('Y-m-d', strtotime('-'.$slider->graph_period.' month')):date('Y-01-01') }}"
												data-period="{{ $slider->graph_period }}"
												data-title="{{ $slider->graph_title }}"
												data-type="{{ $slider->graph_type }}"
												data-country="{{ $slider->country_id }}"
												data-country_name="{{ $slider->country_name }}"
												data-maturity="{{ $slider->option_maturity }}"
												data-price="{{ $slider->option_price }}"
												id="chart_home_{{$slider->id }}" 
												style="width: 100%; height: 440px"></div>
			                            </div>
			                        </div>
			                        <div class="col-md-6">
			                            <div class="text_right" style="color:white;">
			                               	@if(empty($slider->description) && $slider->description == '')
			                               	<?php 
			                               	$desc = $slider->translate('en',true)->description;
			                               	if(empty($desc))
			                               	$desc = $slider->translate('es',true)->description;
			                               	?>
			                               	{!! $desc !!}
			                               	@else
			                               		{!! $slider->description !!}
			                               	@endif
			                            </div>
			                        </div>
			                    </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		@endforeach
    </div>
    @endif
</section>
@stop

@section('scripts')	
	<script src="{{ asset('themes/frontend/js/home.js') }}"></script>	
@stop