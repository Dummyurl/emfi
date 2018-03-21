@extends('layout')

@section('styles')
  <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700" rel="stylesheet">
@endsection

@section('content')
<section class="top_section top_bg economics_bg">
  <div class="container">
    <div class="title_belt">
      <h2>Contact Us</h2>
      <span>How can we help you?</span> </div>
    <div class="contact_form">
      <div class="row">
      <p>If you have any questions, don't hesitate to contact us.</p>
        <form>
    <div class="col-md-6">
          <div class="form-group">
            
            <input class="form-control" id="" placeholder="First Name" type="text">
          </div>
        </div>
    <div class="col-md-6">
          <div class="form-group">
           <input class="form-control" id="" placeholder="Last Name" type="text">
          </div>
        </div>
    <div class="col-md-6">
          <div class="form-group">
           <input class="form-control" id="" placeholder="Country" type="text">
          </div>
        </div>
    <div class="col-md-6">
          <div class="form-group">
           <input class="form-control" id="" placeholder="Email Address" type="text">
          </div>
        </div>
    <div class="col-md-6">
          <div class="form-group">
           <input class="form-control" id="" placeholder="Company" type="text">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
           <input class="form-control" id="" placeholder="Subject" type="text">
          </div>
        </div>
        <div class="col-md-12 txtarea">
          <div class="form-group">
           <textarea name="" cols="" rows="4" class="form-control" placeholder="Message"></textarea>
          </div>
        </div>
        <div class="col-md-12 submit_btn">
          <div class="form-group">
           <button class="btn">Send</button>
          </div>
        </div>
</form>
      </div>
    </div>
  </div>
</section>
<section class="office_details_map">
  <div class="container">
  <div class="row">
    <div class="col-md-4">
      <div class="office_details">
      <h3>LONDON</h3>
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
  <div class="row">
    <div class="col-md-4 col-md-push-8">
      <div class="office_details">
      <h3>ZÜRICH</h3>
      <address>
<strong>EMFI</strong> SECURITIES<br>
Leonhardstrasse 1<br>
8001 Zürich<br>
Switzerland
</address>
<a href="mailto:zurich@emfisecurities.com">zurich@emfisecurities.com</a>
      </div>
      </div>
      <div class="col-md-8 col-md-pull-4">
        <div class="office_map map_mrgn_r">
        <div id="map2" class="map"></div>
        </div>
      </div>
  </div>  
  </div>
</section>

@stop

@section('scripts')
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
<script src="{{ asset('themes/frontend/js/contact.js') }}"></script>
@stop
