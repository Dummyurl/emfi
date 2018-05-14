@extends('emfi_layout')

@section('content')
<section class="home_slider">
    <div class="owl-carousel owl-theme home_carousel">
        @if(!empty($sliders))
            @foreach($sliders as $slider)
        <div class="item">
            <div class="home_slider_item">
                <div class="container">
                    <div class="slider_inner">
                        <div class="title_belt">
                                <h2>{{ $slider['title'] }}</h2>
                                <span>{{ date('F d, Y',strtotime($slider['display_date'])) }}</span>
                        </div>
                            <div class="row">
                                <div class="slider_content">
                                    <div class="col-md-6">
                                            <div class="chart_left"> 
                                                    <div style="display: none;" id="chart-data-{{ $slider['id'] }}">
                                                        {!! json_encode($slider['chart_data']) !!}
                                                    </div>
                                                    <div class="charts-container chart_home"  id="chart-{{ $slider['id'] }}" data-id="{{ $slider['id'] }}" data-type="{{ $slider['graph_type'] }}" data-banchmark="{{ $slider['option_banchmark'] }}" data-prices="{{ $slider['option_prices'] }}"
                                                        style="width: 100%;"></div>
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text_right">
                                                {!! $slider['description'] !!}
                                            <!-- <a href="#">Continue Reading <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
            @endforeach
        @endif
    </div>
</section>
@stop

@section('scripts')
    <script src="{{ asset('themes/frontend/js/home.js') }}"></script>   
@stop
