@extends('layout')

@section('content')

<section class="top_section top_bg economics_bg">
  <div class="container">
    <div class="title_belt">
      <h2>{{ $content->title}}</h2>
      <span>At A Glance</span> </div>
    <div class="about_top_section">
      <div class="row">
        <div class="col-md-12">
          <div class="infoblock">
           {!! $content->description !!}
          </div>
        </div>
    </div>
  </div>
</section>

@stop

@section('scripts')
<script src="{{ asset('themes/frontend/js/about.js') }}"></script>
@stop
