@extends('emfi_layout')

@section('content')

<?php /*
  <section class="top_section top_bg economics_bg">
  <div class="container">
  <div class="title_belt">
  <div class="row">
  <div class="col-md-6">
  <h2>{{ __('country.title') }}</h2>
  <span>{{ date('F d, Y',strtotime($last_update_date)) }}</span>
  </div>
  <div class="col-md-6 select_r">
  </div>
  </div>
  </div>

  </div>
  </section>
 */ ?>

<!-- <div class="clearfix" style="margin-top: 70px;"></div> -->
<input type="hidden" id="main_lang" value="{{ getLangName() }}" />
<input type="hidden" id="defaultCode" value="{{ $defaultCode }}" />
<div id="geo-chart" style="width: 100%; height: 100vh;"></div>

<div class="bottom-select">
    <select name="markets" id="markets">                        
        @foreach($markets as $val => $label)
        @if($val != 3)
        <option value="{{ $val }}" {!! $val == 5 ? 'selected="selected"':'' !!}>
            {{ ucwords(strtolower($label)) }}
        </option>
        @endif
        @endforeach                                                 
    </select>
</div>
@stop

@section('scripts')
<?php /*
  <script type="text/javascript">
  var country_data;
  country_data = JSON.parse('{!! $countries !!}');
  </script>
 */ ?>
<script src="{{ asset('themes/emfi/js/country.js') }}"></script>
@stop