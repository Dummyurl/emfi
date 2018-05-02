@extends('layout')

@section('content')

<section class="top_section top_bg economics_bg">
  <div class="container">
    <div class="title_belt">
      <h2>{{ __('about.about') }}</h2>
      <span>{{ __('contact.at_a_glance') }}</span> </div>
    <div class="about_top_section">
      <div class="row">
        <div class="col-md-3">
          <div class="infoblock"> <i class="fa fa-shield" aria-hidden="true"></i>
            <p>{{ __('about.top_section_1') }}.</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="infoblock"> <i class="fa fa-cube" aria-hidden="true"></i>
            <p>{{ __('about.top_section_2') }}.</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="infoblock"> <i class="fa fa-credit-card" aria-hidden="true"></i>
            <p>{{ __('about.top_section_3') }}.</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="infoblock"> <i class="fa fa-line-chart" aria-hidden="true"></i>
            <p>{{ __('about.top_section_4') }}.</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="infoblock"> <i class="fa fa-comments" aria-hidden="true"></i>
            <p>{{ __('about.top_section_5') }}.</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="infoblock"> <i class="fa fa-bar-chart-o" aria-hidden="true"></i>
            <p>{{ __('about.top_section_6') }}.</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="infoblock"> <i class="fa fa-cubes" aria-hidden="true"></i>
            <p>{{ __('about.top_section_7') }}.</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="infoblock"> <i class="fa fa-fort-awesome" aria-hidden="true"></i>
            <p>{{ __('about.top_section_8') }}.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="info_featured_block">
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-push-6 bgcover imgcol  about-img1"></div>
      <div class="col-md-6 col-md-pull-6 txtcol">
        <h2>{{ __('about.why_emfi') }}?</h2>
        <p>{{ __('about.why_emfi_1') }}.</p>
        <p>{{ __('about.why_emfi_2') }}.</p>
        <p>{{ __('about.why_emfi_3') }}.</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 bgcover imgcol about-img2"> </div>
      <div class="col-md-6 txtcol">
        <h2>{{ __('about.onshore_investor') }}</h2>
        <p>{{ __('about.onshore_investor_1') }}.</p>
        <p>{{ __('about.onshore_investor_2') }}.</p>
        <p>{{ __('about.onshore_investor_3') }}.</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 col-md-push-6 bgcover imgcol about-img3"> </div>
      <div class="col-md-6 col-md-pull-6 txtcol">
        <h2>{{ __('about.offshore_investor') }}</h2>
        <p>{{ __('about.offshore_investor_1') }}.</p>
        <p>{{ __('about.offshore_investor_2') }}.</p>
        <p>{{ __('about.offshore_investor_3') }}.</p>
      </div>
    </div>
  </div>
</section>
<div id="presence"></div>
<section class="our_offices">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="inner_block office_1">
          <div class="txt_block col-lg-3 col-lg-push-1">
            <h2>{{ __('about.london') }}</h2>
            <address>
            <strong>EMFI</strong> SECURITIES<br>
            32 Devonshire Pl<br>
            London, W1G 6JL<br>
            United Kingdom
            </address>
            <a href="mailto:lndon@emfisecurities.com">lndon@emfisecurities.com</a> </div>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="inner_block office_2">
          <div class="txt_block col-lg-3 col-lg-push-8">
            <h2>ZÜRICH</h2>
            <address>
            <strong>EMFI</strong> SECURITIES<br>
            Leonhardstrasse 1<br>
            8001 Zürich<br>
            Switzerland
            </address>
            <a href="mailto:zurich@emfisecurities.com">zurich@emfisecurities.com</a> </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="info_featured_block services">
  <div class="container">
    <div class="row">
      <div class="title">
        <h2>{{ __('about.services') }}</h2>
        <span>{{ __('about.what_we_serve') }}</span> </div>
      <div class="col-md-6 col-md-push-6 bgcover imgcol  about-img4"></div>
      <div class="col-md-6 col-md-pull-6 txtcol">
        <h2>{{ __('about.electronic_execution') }}</h2>
        <p>{{ __('about.electronic_execution_1') }}.</p>
        <p>{{ __('about.electronic_execution_2') }}.</p>
        <p>{{ __('about.electronic_execution_3') }}.</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 bgcover imgcol about-img5"> </div>
      <div class="col-md-6 txtcol">
        <h2>{{ __('about.voice_trading') }}</h2>
        <p>{{ __('about.voice_trading_1') }}.</p>
        <p>{{ __('about.voice_trading_2') }}.</p>
        <p>{{ __('about.voice_trading_3') }}.</p>
      </div>
    </div>
  </div>
</section>
<div id="clients"></div>
<section class="clients">
  <div class="container">
    <div class="row">
      <div class="innerwrapper clearfix">
        <div class="left_col">
          <h2>{{ __('about.clients') }}</h2>
          <p>{{ __('about.client_desc') }}.</p>
        </div>
        <div class="right_btns">
          <ul>
            <li><a href="#">{{ __('about.client_link_1') }}</a></li>
            <li><a href="#">{{ __('about.client_link_2') }}</a></li>
            <li><a href="#">{{ __('about.client_link_3') }}</a></li>
            <li><a href="#">{{ __('about.client_link_4') }}</a></li>
            <li><a href="#">{{ __('about.client_link_5') }}</a></li>
            <li><a href="#">{{ __('about.client_link_6') }}</a></li>
            <li><a href="#">{{ __('about.client_link_7') }}</a></li>
            <li><a href="#">{{ __('about.client_link_8') }}</a></li>
            <li><a href="#">{{ __('about.client_link_9') }}</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="our_value">
  <div class="container">
    <div class="row">
      <div class="innerwrapper clearfix">
        <h2>{{ __('about.our_value') }}</h2>
        <div class="our_value_item">
          <h3>{{ __('about.value_tit_1') }}</h3>
          <p>{{ __('about.value_desc_1') }}. </p>
        </div>
        <div class="our_value_item">
          <h3>{{ __('about.value_tit_2') }}</h3>
          <p>{{ __('about.value_desc_2') }}.</p>
        </div>
        <div class="our_value_item">
          <h3>{{ __('about.value_tit_3') }}</h3>
          <p>{{ __('about.value_desc_3') }}.</p>
        </div>
        <div class="our_value_item">
          <h3>{{ __('about.value_tit_4') }}</h3>
          <p>{{ __('about.value_desc_4') }}.</p>
        </div>
      </div>
    </div>
  </div>
</section>
<div id="candidates"></div>
<section class="our_value">
  <div class="container">
    <div class="row">
      <div class="innerwrapper clearfix">
        <h2>Candidates</h2>
        <div class="our_value_item">
          <h3>{{ __('about.value_tit_5') }}</h3>
          <p>{{ __('about.value_desc_5') }}.</p>
        </div>
        <div class="our_value_item">
          <h3>{{ __('about.value_tit_6') }}</h3>
          <p>{{ __('about.value_desc_6') }}.</p>
        </div>
        <div class="our_value_item">
          <h3>{{ __('about.value_tit_7') }}</h3>
          <p>{{ __('about.value_desc_7') }}.</p>
        </div>        
      </div>
    </div>
  </div>
</section>

<section class="career">
  <div class="container">
    <div class="row">
      <div class="innerwrapper clearfix">
        <div class="col-md-6 left_col">
         <h2>{{ __('about.careers') }}</h2>
         <p>{{ __('about.careers_1') }}.</p>
      <p>{{ __('about.careers_2') }}.</p>
      <p>{{ __('about.careers_3') }} <a href="mailto:careers@emfisecurities.com">careers@emfisecurities.com</a></p>
        </div>
        <div class="col-md-6 form_col">
      <!-- <form> -->
      {!! Form::open(['url' => '/about-form', 'id' => 'careers_form', 'method' => 'POST']) !!}       
        <div class="col-md-6">
          <div class="form-group">
            {!! Form::text('first_name', null, ['class' => 'form-control' , 'placeholder' => __('about.first_name')]) !!}
            <!-- <input class="form-control" id="" placeholder="{{ __('about.first_name') }}" type="text"> -->
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            {!! Form::text('last_name', null, ['class' => 'form-control' , 'placeholder' => __('about.last_name')]) !!}            
           <!-- <input class="form-control" id="" placeholder="{{ __('about.last_name') }}" type="text"> -->
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            {!! Form::text('country', null, ['class' => 'form-control' , 'placeholder' => __('about.country')]) !!}                        
           <!-- <input class="form-control" id="" placeholder="{{ __('about.country') }}" type="text"> -->
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            {!! Form::text('email', null, ['class' => 'form-control' , 'placeholder' => __('about.email')]) !!}            
           <!-- <input class="form-control" id="" placeholder="{{ __('about.email') }}" type="text"> -->
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            {!! Form::text('subject', null, ['class' => 'form-control' , 'placeholder' => __('about.subject')]) !!}            
            <!-- <input class="form-control" id="" placeholder="{{ __('about.subject') }}" type="text"> -->
          </div>
        </div>
        <div class="col-md-12 txtarea">
          <div class="form-group">
            {!! Form::textarea('message', null, ['class' => 'form-control' , 'rows' => 4 , 'placeholder' => __('about.message')]) !!}                        
            <!-- <textarea name="" cols="" rows="4" class="form-control" placeholder="{{ __('about.message') }}"></textarea> -->
          </div>
        </div>
        <div class="col-md-12 submit_btn">
          <div class="form-group">
           <button class="btn">{{ __('about.send') }}</button>
          </div>
        </div>
      {!! Form::close() !!}      
      <!-- </form> -->
          
        </div>
      </div>
    </div>
  </div>
</section>

@stop

@section('scripts')
<script src="{{ asset('themes/frontend/js/about.js') }}"></script>
@stop
