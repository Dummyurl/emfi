@extends('emfi_layout')

@section('content')

<section class="top_section">
  <div class="container">
    <div class="title_belt">
      <h2>{{ $content->title}}</h2>
        <span>{{ __('contact.at_a_glance') }}</span>
    </div>
    <div class="about_top_section">
      <div class="row">
        <div class="col-md-12">
          <div class="terms_block">
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
