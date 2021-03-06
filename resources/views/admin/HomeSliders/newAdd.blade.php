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
							<div class="row">
								<div class="col-md-12">
									<label class="control-label">Country (Blank if provide for all countries)</label>
									{!! Form::select('country_id',[''=>'Select Country']+ $countries,null,['class' => 'country form-control', 'data-required' => true]) !!}
								</div>
							</div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row" style="display: none;" id="graph_type_row">
                                <div class="col-md-12" >
                                    <label class="control-label">Graph Type<span class="required">*</span></label>
                                    {!! Form::select('graph_type',[''=>'Select Graph Type']+$graphTypes,null,['class' => 'form-control', 'data-required' => true,'id'=>'graph_type_id']) !!}
                                </div>
                            </div>
                        <div class="down_content" style="display: none;">
                            <div class="row">
                            <div class="clearfix">&nbsp;</div>
                                <div class="col-md-12" >
                                    <label class="control-label">Graph Period<span class="required">*</span></label>
                                    {!! Form::select('graph_period',[''=>'Select Period']+$months,null,['class' => 'form-control', 'data-required' => true,'id'=>'graph_period_id']) !!}
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row" id="security_id" style="display: none;">
                                <div class="col-md-12">
									<label class="control-label">Security<span class="required">*</span></label>
									{!! Form::select('security_id',[''=>'Select Security']+$graphs,null,['class' => 'form-control graphs' , 'data-required' => false,'id'=>'security_id_val']) !!}
								</div>
                            </div>
                            <div id="yield_curve_div" style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Maturity/Duration<span class="required">*</span></label>
                                    {!! Form::select('option_maturity',[''=>'Select Option']+$maturities,null,['class' => 'form-control','id'=>'option_maturity_id']) !!}
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Price<span class="required">*</span></label>
                                    {!! Form::select('option_price',[''=>'Select Option']+$prices,'price',['class' => 'form-control','id'=>'option_price_id']) !!}
                                </div>
                            </div>
                            </div>
                            <div class="row" style="display: none;">
								<div class="col-md-12">
									<label class="control-label">Order<span class="required">*</span></label>
									{!! Form::number('order',$orderMax,['class' => 'form-control', 'data-required' => true,'min'=>1]) !!}
								</div>
                            </div>
                            <div class="row" style="display: none;">
                                <div class="col-md-12">
                                <div class="clearfix">&nbsp;</div>
									<label class="control-label">Status<span class="required">*</span></label>
									{!! Form::select('status',[1=>'Active',0=>'Inactive'],null,['class' => 'form-control', 'data-required' => true]) !!}
								</div>
							</div>
                            
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
									{!! Form::text('post_title['.$lng.'][]',$title,['class' => 'form-control']) !!}
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
    $(document).ready(function(){

        $('.country').on('change',function(){
            var country_val = $('.country').val();
            if(country_val != '')
            {
                $('#graph_type_row').show();
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
                $('#security_id').show();
                $('#security_id_val').attr('disabled',false);
                $('#yield_curve_div').hide();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == 'yield_curve'){
                $('.down_content').show();
                $('#security_id').hide();
                $('#security_id_val').attr('disabled',true);
                $('#yield_curve_div').show();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == ''){
                $('.down_content').show();
                $('#security_id').hide();
                $('#security_id_val').attr('disabled',true);
                $('#yield_curve_div').hide();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else{
                alert('Please select valid graph type !');
                $('#AjaxLoaderDiv').fadeOut('slow');
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
