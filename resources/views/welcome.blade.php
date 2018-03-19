@extends('layout')

@section('content')
<section class="home_slider">
    <div class="owl-carousel owl-theme home_carousel">
        <div class="item">
            <div class="home_slider_item bgcover" style="background:url({{ asset('themes/frontend/images/home-bg-1.jpg') }})">
                <div class="container">
                    <div class="title_belt">
                        <h2>Analyer</h2>
                        <span>February 14, 2018</span>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="chart_left">
                                <div id="chart_home" style="width: 100%; height: 440px"></div>                                    
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text_right">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pharetra eget metus eu volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean vitae sollicitudin justo. Donec nec pretium odio. Aliquam sed magna mi. Suspendisse bibendum, metus vel</p>
                                <p>Integer sed tincidunt dui. Cras tincidunt at risus vitae ultrices. Sed at placerat diam. Nam ornare feugiat blandit. Suspendisse potenti. Nam et finibus elit. Integer ac turpis quam. Pellentesque eleifend ipsum a magna rhoncus, eu laoreet ipsum dapibus. Morbi pellentesque, nunc at pulvinar fringilla, nunc risus iaculis enim, finibus ornare turpis sem quis ex. Maecenas tristique varius orci vel eleifend. Sed tempor erat id mi tempor efficitur. Integer sed tincidunt dui. Cras tincidunt at risus vitae ultrices. Sed at placerat diam. Nam ornare feugiat blandit.</p>
                                <a href="#">Continue Reading <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="home_slider_item bgcover" style="background:url({{ asset('themes/frontend/images/home-bg-1.jpg') }})">
                <div class="container">
                    <div class="title_belt">
                        <h2>Analyer</h2>
                        <span>February 14, 2018</span>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="chart_left"> <div id="chart_home2" style="width: 100%; height: 440px;"> </div> </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text_right">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pharetra eget metus eu volutpat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean vitae sollicitudin justo. Donec nec pretium odio. Aliquam sed magna mi. Suspendisse bibendum, metus vel</p>
                                <p>Integer sed tincidunt dui. Cras tincidunt at risus vitae ultrices. Sed at placerat diam. Nam ornare feugiat blandit. Suspendisse potenti. Nam et finibus elit. Integer ac turpis quam. Pellentesque eleifend ipsum a magna rhoncus, eu laoreet ipsum dapibus. Morbi pellentesque, nunc at pulvinar fringilla, nunc risus iaculis enim, finibus ornare turpis sem quis ex. Maecenas tristique varius orci vel eleifend. Sed tempor erat id mi tempor efficitur. Integer sed tincidunt dui. Cras tincidunt at risus vitae ultrices. Sed at placerat diam. Nam ornare feugiat blandit.</p>
                                <a href="#">Continue Reading <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop