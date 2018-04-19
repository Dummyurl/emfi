@extends('layout')

@section('content')
<section class="top_section top_bg economics_bg">
  <div class="container">
        <div class="title_belt">            
            <div class="row">
                <div class="col-md-6">
                    <h2>{{ __('country.title') }}</h2>
                    <span>{{ date('F d, Y',strtotime($last_update_date)) }}</span>
                </div>
                <div class="col-md-6 select_r">
                    <select name="markets" id="markets">                        
                        @foreach($markets as $val => $label)
                        <option value="{{ $val }}" {!! $val == 5 ? 'selected="selected"':'' !!}>
                            {{ ucwords(strtolower($label)) }}
                        </option>
                        @endforeach                                                 
                    </select>
                </div>
            </div>
        </div>
        <div id="geo-chart" style="width: 100%; height: 600px;"></div>
  </div>
</section>  	
@stop

@section('scripts')
	<?php /*
	<script type="text/javascript">
		var country_data;
		country_data = JSON.parse('{!! $countries !!}');
	</script>
	*/ ?>
	<script src="{{ asset('themes/frontend/js/country.js') }}"></script>
@stop