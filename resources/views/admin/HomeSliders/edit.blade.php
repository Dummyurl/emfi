@extends('admin.layouts.app')

@section('breadcrumb')

@stop

@section('content')

<div class="page-content">
    <div class="container">
        <div class="row autoResizeHeight">
            <div class="col-md-12">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-file"></i> {{ $page_title }}
                        </div>
                        <a class="btn btn-default pull-right btn-sm mTop5" href="{{ $list_url }}">Back</a>
                    </div>
                    <div class="portlet-body">
                        <div class="form-body">
                            {!! Form::model($formObj,['method' => $method,'files' => true, 'route' => [$action_url,$action_params],'class' => 'sky-form form form-group', 'id' => 'main-frm']) !!}
                    <!-- Country -->
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Country <span class="required">*</span></label>
                                    {!! Form::select('country_id',[''=>'Select Country']+ $countries,null,['class' => 'country form-control', 'data-required' => true]) !!}
                                </div>
                            </div>
                    <!-- Graph Type -->
                            <div class="clearfix">&nbsp;</div>
                            <div class="row" style="display: none;" id="graph_type_row">
                                <div class="col-md-12" >
                                    <label class="control-label">Graph Type<span class="required">*</span></label>
                                    {!! Form::select('graph_type',[''=>'Select Graph Type']+$graphTypes,null,['class' => 'form-control', 'data-required' => true,'id'=>'graph_type_id']) !!}
                                </div>
                            </div>
                        <div class="down_content" style="display: none;">
                        <!-- Security -->
                        <div class="row" id="security_id" style="display: none;">
                        <div class="clearfix">&nbsp;</div>
                            <div class="col-md-12">
                                <label class="control-label">Security<span class="required">*</span></label>
                                {!! Form::select('security_id',[''=>'Select Security']+$graphs,null,['class' => 'form-control graphs' , 'data-required' => false,'id'=>'security_id_val']) !!}
                            </div>
                        </div>
                        <!-- Second security -->
                        <div class="row" id="option_security_div" style="display: none;">
                            <div class="clearfix">&nbsp;</div>
                                <div class="col-md-12" >
                                <label class="control-label">Second Security<span class="required">*</span></label>
                                {!! Form::select('option_security',[''=>'Select Security']+$option_security,null,['class' => 'form-control' , 'data-required' => false,'id'=>'option_security_val']) !!}
                            </div>
                        </div>
                        <!--  Period -->
                        <div  id="period_div" style="display: none;">
                        <div class="clearfix">&nbsp;</div>
                        <div class="row">
                            <div class="col-md-12" >
                                    <label class="control-label">Graph Period<span class="required">*</span></label>
                                {!! Form::select('option_period',[''=>'Select Period']+$months,$selected_month,['class' => 'form-control', 'data-required' => false,'id'=>'graph_period_id']) !!}
                            </div>
                                </div>
                            </div>
                        <!-- Price -->
                        <div id="prices_div" style="display: none;">
                            <div class="clearfix">&nbsp;</div>
                        <div class="row">
                                <div class="col-md-12">
                                <label class="control-label">Price<span class="required">*</span></label>
                                {!! Form::select('option_prices',[''=>'Select Option']+$prices,null,['class' => 'form-control','id'=>'option_price_id']) !!}
                                </div>
                            </div>
                        </div>
                        <!-- Maturity -->
                        <div id="maturity_div" style="display: none;">
                        <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Maturity / Duration<span class="required">*</span></label>
                                    {!! Form::select('option_maturity',[''=>'Select Option']+$maturities,null,['class' => 'form-control','id'=>'option_maturity_id']) !!}
                                </div>
                            </div>
                        </div>
                        <!-- Markets -->
                        <div id="market_div" style="display: none;">
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-12">
                                <label class="control-label">Markets<span class="required">*</span></label>
                                {!! Form::select('option_market',[''=>'Select Option']+$marketTypes,null,['class' => 'form-control','id'=>'option_market_id']) !!}
                            </div>
                        </div>
                        </div>
                        <!-- Rating -->
                        <div id="rating_div"  style="display: none;">
                        <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Rating / OECD <span class="required">*</span></label>
                                    {!! Form::select('option_rating',[''=>'Select Option']+$rating_orcd,null,['class' => 'form-control', 'data-required' => false, 'id'=>'option_maturity_id']) !!}
                                </div>
                            </div>
                            </div>
                        <!-- Banchmark -->
                        <div id="banchmark_div"  style="display: none;">
                        <div class="clearfix">&nbsp;</div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label">Banchmark</label>
                                {!! Form::select('option_banchmark',[''=>'Select Option'],null,['class' => 'form-control','id'=>'option_banchmark']) !!}
                            </div>
                        </div>
                        </div>
                        <!-- Credit Equities -->
                        <div id="credit_div"  style="display: none;">
                        <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Credit / Equities <span class="required">*</span></label>
                                    {!! Form::select('option_credit',[''=>'Select Option']+$credit_equities,null,['class' => 'form-control', 'data-required' => false, 'id'=>'option_maturity_id']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                            <div class="row" style="display: none;">
                                <div class="col-md-12">
                                    <label class="control-label">Order<span class="required">*</span></label>
                            {!! Form::number('order',$orderMax,['class' => 'form-control', 'data-required' => false,'min'=>1]) !!}
                                </div>
                            </div>
                            <div class="row" style="display: none;">
                                <div class="col-md-12">
                                <div class="clearfix">&nbsp;</div>
                                    <label class="control-label">Status<span class="required">*</span></label>
                            {!! Form::select('status',[1=>'Active',0=>'Inactive'],null,['class' => 'form-control', 'data-required' => false]) !!}
                                </div>
                            </div>
                		<div> 
                            
                            @foreach($languages as $lng => $val)
                            <?php 
                                $title = null;
                                $description = null;
                                if(isset($formObj->id) && !empty($formObj->id)){
                                $trans =  \App\Models\HomeSliderTranslation::where('locale',$lng)->where('home_slider_id',$formObj->id)->first();
                                if($trans)
                                {
                                    $title = $trans->title;
                                    $description = $trans->description;
                                }
                            }?>
                            <div class="clearfix">&nbsp;</div>
                            <div class="note note-info">
                                <div class="row">
                                    <div class="col-md-10" style="padding-left: 30px; height: 14px;">
                                        <h4>For {{ $val }}</h4>
                                    </div>   
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="" class="control-label">Post Title [{{ $lng }}]
                                    </label>
                            {!! Form::text('post_title['.$lng.'][]',$title,['class' => 'form-control setValue']) !!}
                                </div>
                                <div class="clearfix">&nbsp;</div>
                                <div class="col-md-12">
                            <label for="" class="control-label">Post Description [{{ $lng }}]
                            </label>
                                    {!! Form::textarea('post_description['.$lng.'][]',$description,['class' => 'form-control ckeditor']) !!}
                                </div>
                            </div>
                            @endforeach
                        </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="submit" value="Save" class="btn btn-success pull-right" />
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script type="text/javascript">
    function getBanchmarkViaCountry(country_id)
    {
        $('#AjaxLoaderDiv').fadeIn('slow');
        var country_val = country_id;

        $.ajax({
            type: "GET",
            url: "/admin/getcountries/banchmark/",
            data: {country_id:country_val},
            success: function (result)
            {
                var $country = $('#option_banchmark');
                $country.empty();
                $country.append('<option>Select Banchmark</option>');
                $.each(result, function(k, v) {
                    if(v.country_id != country_val){
                        $country.append('<option value="' + v.country_id + '">' + v.country_title + '</option>');
                    }
                });
                $('#AjaxLoaderDiv').fadeOut('slow');
            },
            error: function (error) {
            }
        });
    }
    function getBanchmarkViaSecurity(security_id,option_banchmark)
    {
        $('#AjaxLoaderDiv').fadeIn('slow');
        $.ajax({
            type: "GET",
            url: "/admin/getsecurities/banchmark/",
            data: {security_id:security_id},
            success: function (result)
            {
                if(result.price == 5){
                    $('#prices_div').show();
                }
                var $country = $('#option_banchmark');
                $country.empty();
                $country.append('<option>Select Banchmark</option>');
                $.each(result.banchmark, function(k, v) {
                    $country.append('<option value="' + v.id + '">' + v.title + '</option>');
                });
                $('#option_banchmark').val(option_banchmark);
                $('#AjaxLoaderDiv').fadeOut('slow');
                $country.change();
            },
            error: function (error) {
            }
        });
    }
    function getSecurity(country_id)
    {
        $('#AjaxLoaderDiv').fadeIn('slow');
        var country_val = country_id;
        $.ajax({
            type: "GET",
            url: "/admin/getsecurities/",
            data: {country_id:country_val},
            success: function (result)
            {
                var $country = $('#security_id_val');
                $country.empty();
                $country.append('<option>Select Security</option>');
                $.each(result, function(k, v)
                {
                    $country.append('<option value="' + k + '">' + v + '</option>');
                });
                $('#AjaxLoaderDiv').fadeOut('slow');
                $country.change();
            },
            error: function (error) {
            }
        });
    }
</script>
<script type="text/javascript">
    $(document).ready(function(){
        var formdata = '{{ $formObj }}';
        var option_prices = '{{ $formObj->option_prices }}';
        var country_id = '{{ $formObj->country_id }}';
        var option_banchmark = '{{ $formObj->option_banchmark }}';

        if(formdata != '')
        {
            $('#graph_type_row').show();
            $('.down_content').show();
            var graph_val = $('#graph_type_id').val();
            if(graph_val == 'line'){
                $('.down_content').show();
                $('#period_div').show();
                $('#prices_div').hide();
                $('#maturity_div').hide();
                $('#market_div').hide();
                $('#security_id').show();
                $('#security_id_val').attr('disabled',false);
                $('#rating_div').hide();
                $('#banchmark_div').show();
                $('#credit_div').hide();
                $('#option_security_div').hide();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == 'yield_curve')
            {
                $('.down_content').show();
                $('#period_div').show();
                $('#prices_div').show();
                $('#maturity_div').show();
                $('#market_div').hide();
                $('#security_id').hide();
                $('#security_id_val').attr('disabled',true);
                $('#rating_div').hide();
                $('#banchmark_div').show();
                $('#credit_div').hide();
                $('#option_security_div').hide();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == 'market_movers_gainers' || graph_val == 'market_movers_laggers')
            {
                $('.down_content').show();
                $('#period_div').hide();
                $('#prices_div').hide();
                $('#maturity_div').hide();
                $('#market_div').show();
                $('#security_id').hide();
                $('#security_id_val').attr('disabled',true);
                $('#rating_div').hide();
                $('#banchmark_div').hide();
                $('#credit_div').hide();
                $('#option_security_div').hide();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == 'market_history'){
                $('.down_content').show();
                $('#period_div').show();
                if(option_prices != ''){
                    $('#prices_div').show();
                }
                $('#maturity_div').hide();
                $('#market_div').hide();
                $('#security_id').show();
                $('#security_id_val').attr('disabled',false);
                $('#rating_div').hide();
                $('#banchmark_div').show();
                $('#credit_div').hide();
                $('#option_security_div').hide();
                getBanchmarkViaSecurity('{{$formObj->security_id}}',option_banchmark)
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == 'differential'){
                $('.down_content').show();
                $('#period_div').show();
                $('#prices_div').show();
                $('#maturity_div').hide();
                $('#market_div').hide();
                $('#security_id').show();
                $('#security_id_val').attr('disabled',false);
                $('#rating_div').hide();
                $('#banchmark_div').hide();
                $('#credit_div').hide();
                $('#option_security_div').show();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == 'regression'){
                $('.down_content').show();
                $('#period_div').show();
                $('#prices_div').show();
                $('#maturity_div').hide();
                $('#market_div').hide();
                $('#security_id').show();
                $('#security_id_val').attr('disabled',false);
                $('#rating_div').hide();
                $('#banchmark_div').hide();
                $('#credit_div').hide();
                $('#option_security_div').show();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == 'relative_value'){
                $('.down_content').show();
                $('#period_div').show();
                $('#prices_div').show();
                $('#maturity_div').hide();
                $('#market_div').hide();
                $('#security_id').hide();
                $('#security_id_val').attr('disabled',true);
                $('#rating_div').show();
                $('#banchmark_div').hide();
                $('#credit_div').show();
                $('#option_security_div').hide();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else{
                $('.down_content').hide();
                alert('Invalid graph type !');
            }
        }

        $('.country').on('change',function(){
            var country_val = $('.country').val();
            if(country_val != '')
            {
                $('#graph_type_row').show();
                getSecurity(country_val);
                getBanchmarkViaCountry(country_val);
            }else{
                $('#graph_type_row').hide();
                $('.down_content').hide();
            }
        });

        $('#graph_type_id').on('change',function(){

            $('#AjaxLoaderDiv').fadeIn('slow');
            var graph_val = $('#graph_type_id').val();
            if(graph_val == 'line'){
                $('.down_content').show();
                $('#period_div').show();
                $('#prices_div').hide();
                $('#maturity_div').hide();
                $('#market_div').hide();
                $('#security_id').show();
                $('#security_id_val').attr('disabled',false);
                $('#rating_div').hide();
                $('#banchmark_div').show();
                $('#credit_div').hide();
                $('#option_security_div').hide();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == 'yield_curve'){
                $('.down_content').show();
                $('#period_div').show();
                $('#prices_div').show();
                $('#maturity_div').show();
                $('#market_div').hide();
                $('#security_id').hide();
                $('#security_id_val').attr('disabled',true);
                $('#rating_div').hide();
                $('#banchmark_div').show();
                $('#credit_div').hide();
                $('#option_security_div').hide();
                
                getBanchmarkViaCountry($('.country').val());
                $('#AjaxLoaderDiv').fadeOut('slow');
            }else if(graph_val == 'market_movers_gainers' || graph_val == 'market_movers_laggers'){
                $('.down_content').show();
                $('#period_div').hide();
                $('#prices_div').hide();
                $('#maturity_div').hide();
                $('#market_div').show();
                $('#security_id').hide();
                $('#security_id_val').attr('disabled',true);
                $('#rating_div').hide();
                $('#banchmark_div').hide();
                $('#credit_div').hide();
                $('#option_security_div').hide();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == 'market_history'){
                $('#AjaxLoaderDiv').fadeIn('slow');
                $('.down_content').show();
                $('#period_div').show();
                $('#prices_div').hide();
                $('#maturity_div').hide();
                $('#market_div').hide();
                $('#security_id').show();
                $('#security_id_val').attr('disabled',false);
                $('#rating_div').hide();
                $('#banchmark_div').show();
                $('#credit_div').hide();
                $('#option_security_div').hide();
                
                getSecurity($('.country').val());
                $('#option_banchmark').empty();
                $('#option_banchmark').append('<option>Select Banchmark</option>');
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == 'differential'){
                $('.down_content').show();
                $('#period_div').show();
                $('#prices_div').show();
                $('#maturity_div').hide();
                $('#market_div').hide();
                $('#security_id').show();
                $('#security_id_val').attr('disabled',false);
                $('#rating_div').hide();
                $('#banchmark_div').hide();
                $('#credit_div').hide();
                $('#option_security_div').show();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == 'regression'){
                $('.down_content').show();
                $('#period_div').show();
                $('#prices_div').show();
                $('#maturity_div').hide();
                $('#market_div').hide();
                $('#security_id').show();
                $('#security_id_val').attr('disabled',false);
                $('#rating_div').hide();
                $('#banchmark_div').hide();
                $('#credit_div').hide();
                $('#option_security_div').show();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == 'relative_value'){
                $('.down_content').show();
                $('#period_div').show();
                $('#prices_div').show();
                $('#maturity_div').hide();
                $('#market_div').hide();
                $('#security_id').hide();
                $('#security_id_val').attr('disabled',true);
                $('#rating_div').show();
                $('#banchmark_div').hide();
                $('#credit_div').show();
                $('#option_security_div').hide();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == ''){
                $('.down_content').show();
                $('#period_div').hide();
                $('#prices_div').hide();
                $('#maturity_div').hide();
                $('#market_div').hide();
                $('#security_id').hide();
                $('#rating_div').hide();
                $('#banchmark_div').hide();
                $('#credit_div').hide();
                $('#option_security_div').hide();
                $('#security_id_val').attr('disabled',true);
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else{
                alert('Please select valid graph type !');
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
        });
        $('#security_id_val').on('change',function(){
            var security_id = $('#security_id_val').val();
            if(security_id > 0){
                getBanchmarkViaSecurity(security_id,null)
            }
        });
        
        $('#main-frm').submit(function () {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            if ($(this).parsley('isValid'))
            {
                $('#AjaxLoaderDiv').fadeIn('slow');
                $.ajax({
                    type: "POST",
                    url: $(this).attr("action"),
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    enctype: 'multipart/form-data',
                    success: function (result)
                    {
                        $('#AjaxLoaderDiv').fadeOut('slow');
                        if (result.status == 1)
                        {
                            $.bootstrapGrowl(result.msg, {type: 'success', delay: 4000});
                            window.location = '{{ $list_url }}';
                        }
                        else
                        {
                            $.bootstrapGrowl(result.msg, {type: 'danger', delay: 4000});
                        }
                    },
                    error: function (error) {
                        $('#AjaxLoaderDiv').fadeOut('slow');
                        $.bootstrapGrowl("Internal server error !", {type: 'danger', delay: 4000});
                    }
                });
            }

            return false;
        });
    });
</script>
@endsection
