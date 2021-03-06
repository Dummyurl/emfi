@extends('emfi_layout')

@section('content')
<section class="top_section top_bg economics_bg">
  <div class="container">
    <div class="title_belt">
      <h2>{{ __('about.about') }}</h2>
      <span>{{ __('contact.at_a_glance') }}</span> 
    </div>
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
        <!-- <div class="col-md-3">
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
        </div> -->
      </div>
    </div>
  </div>
</section>
<section class="info_featured_block">
  <div id="why_emfi">
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-md-push-6 bgcover imgcol  about-img1"></div>
        <div class="col-md-6 col-md-pull-6 txtcol">
          <h2>{{ __('about.why_emfi') }}</h2>
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
  </div>
</section>
<section class="team_member">
  <div class="container" id="team">
  <h2>{{ __('about.team') }}</h2>
    <div class="row">
    @foreach($teams as $team)
    <div class="col-md-4 col-sm-6">
      <div class="team_block">
        <figure><img src="/themes/emfi/images/no-image-male.png" alt="Frank Sinatra - Project Manager"></figure>
        <h3>{{ $team['name'] }}</h3>
        <h4>{{ $team['post'] }}</h4>
        <a href="{{ $team['linkedin'] }}" target="_blank"><i aria-hidden="true" class="fa fa-linkedin"></i></a></div>
    </div>
    @endforeach
    </div>
    </div>
    
</section>
<section class="info_block_new">
  <div id="clients">
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-md-push-6 bgcover about-img1 inner_1"></div>
        <div class="col-md-6 col-md-pull-6 txtcol inner_1 clients-section">
          <div class="info_content_left">
            <div class="text-justify">
              <h2>{{ __('about.clients') }}</h2>
              <p>{{ __('about.client_desc') }}.</p>
            </div>
            <div class="btns">
              <ul class="clearfix">
                <li><p><a href="#">{{ __('about.client_link_1') }}</a></p></li>
                <li><p><a href="#">{{ __('about.client_link_2') }}</a></p></li>
                <li><p><a href="#">{{ __('about.client_link_3') }}</a></p></li>
                <li><p><a href="#">{{ __('about.client_link_4') }}</a></p></li>
                <li><p><a href="#">{{ __('about.client_link_5') }}</a></p></li>
                <li><p><a href="#">{{ __('about.client_link_6') }}</a></p></li>
                <li><p><a href="#">{{ __('about.client_link_7') }}</a></p></li>
                <li><p><a href="#">{{ __('about.client_link_8') }}</a></p></li>
                <li><p><a href="#">{{ __('about.client_link_9') }}</a></p></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-6 bgcover about-img2 inner_2"></div>
        <div class="col-md-6 txtcol inner_2">
          <div class="innerdiv">
            <h2>{{ __('about.our_value') }}</h2>
            <div class="text-justify">
              <h3>{{ __('about.value_tit_1') }}</h3>
              <p>{{ __('about.value_desc_1') }}. </p>
            </div>
            <div class="text-justify">
              <h3>{{ __('about.value_tit_2') }}</h3>
              <p>{{ __('about.value_desc_2') }}.</p>
            </div>
            <div class="text-justify">
              <h3>{{ __('about.value_tit_3') }}</h3>
              <p>{{ __('about.value_desc_3') }}.</p>
            </div>
            <div class="text-justify">
              <h3>{{ __('about.value_tit_4') }}</h3>
              <p>{{ __('about.value_desc_4') }}.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="our_value">
  <div id="candidates">
  <div class="container">
      <div class="row">
        <div class="col-md-6 col-md-push-6 bgcover about-img1 inner_3"></div>
        <div class="col-md-6 col-md-pull-6 txtcol inner_3">
          <div class="content_left">
            <div class="text-justify">
              <h2>{{ __('about.candidates') }}</h2>
              <p>{{ __('about.candidates_desc_1') }}</p>
              <p>{{ __('about.candidates_desc_2') }}</p>
              <p>{{ __('about.candidates_desc_3') }} <a href="{{ url(getLangName().'/contact/careers')}}">here</a>.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
      <div class="col-md-6 bgcover imgcol about-img2 inner_4"> </div>
        <div class="col-md-6 txtcol inner_4">
          <div class="content_right clearfix">
            <h2>{{ __('about.our_value') }}</h2>
            <div class="text-justify">
              <h3>{{ __('about.value_tit_5') }}</h3>
              <p>{{ __('about.value_desc_5') }}.</p>
            </div>
            <div class="text-justify">
              <h3>{{ __('about.value_tit_6') }}</h3>
              <p>{{ __('about.value_desc_6') }}.</p>
            </div>
            <div class="text-justify">
              <h3>{{ __('about.value_tit_7') }}</h3>
              <p>{{ __('about.value_desc_7') }}.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="our_offices">
  <div id="presence">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="inner_block office_1">
            <div class="txt_block col-lg-3 col-md-4 col-sm-5 col-lg-push-1">
              <h2>{{ __('about.london') }}</h2>
              <address>
              <strong>EMFI</strong> Group Limited<br><br>
              32 Devonshire Pl<br>
              London, W1G 6JL<br>
              United Kingdom
              </address>
              <a href="mailto:london@emfi.eu">london@emfi.eu</a> </div>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="inner_block office_2">
            <div class="txt_block col-lg-3 col-md-4 col-sm-5 col-lg-push-8">
              <h2>ZURICH</h2>
              <address>
              <strong>EMFI</strong> Group Limited<br><br>
              Leonhardstrasse 1<br>
              8001 Zürich<br>
              Switzerland
              </address>
              <a href="mailto:zurich@emfi.eu">zurich@emfi.eu</a> </div>
          </div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="col-lg-12">
          <div class="inner_block office_3 ">
            <div class="txt_block col-lg-3 col-md-4 col-sm-5 col-lg-push-1">
              <h2>NEW YORK</h2>
              <address>
              <strong>EMFI</strong> Group Limited<br><br>
              598 9th Ave<br>
              New York, NY 10036<br>
              United States
              </address>
              <a href="mailto:newyork@emfi.eu">newyork@emfi.eu</a> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--
  <div class="section-block team-2 bkg-grey-ultralight">
    <div class="row">
      <div class="column width-12">
        <h2 class="mb-30">TEAM</h2>
      </div>
      <div class="column width-12 horizon" data-animate-in="preset:slideInUpShort;duration:1000ms;" data-threshold="0.4">
        <div class="row content-grid-3">
          <div class="grid-item">
            <div class="team-content">
              <div class="thumbnail no-margin-bottom">
                <img src="images/architecture/generic/team-member-2.jpg" alt="team memeber 1" width="760" height="500"/>
              </div>
              <div class="team-content-info">
                <h5 class="mb-5">Frank Sinatra</h5>
                <h6 class="occupation mb-50">Project Manager</h6>
              </div>
            </div>
          </div>
          <div class="grid-item">
            <div class="team-content">
              <div class="thumbnail no-margin-bottom">
                <img src="images/architecture/generic/team-member-3.jpg" alt="team memeber 2" width="760" height="500"/>
              </div>
              <div class="team-content-info">
                <h5 class="mb-5">Jason Adams</h5>
                <h6 class="occupation mb-50">Project Manager</h6>
              </div>
            </div>
          </div>
          <div class="grid-item">
            <div class="team-content">
              <div class="thumbnail no-margin-bottom">
                <img src="images/architecture/generic/team-member-1.jpg" alt="team memeber 3" width="760" height="500"/>
              </div>
              <div class="team-content-info">
                <h5 class="mb-5">George Clooney</h5>
                <h6 class="occupation mb-50">Project Manager</h6>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> -->
<?php /*
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
</section>*/
?>
@stop

@section('scripts')
<script src="{{ asset('themes/emfi/js/about.js') }}"></script>
@stop 