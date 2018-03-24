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
                                    <label class="control-label">Slider Type<span class="required">*</span></label>
                                    {!! Form::select('slider_type',[''=>'Select Type','post'=>'Post','graph'=>'Graph'],null,['class' => 'form-control','id'=>'slider_id', 'data-required' => true, 'disabled'=>'disabled']) !!}
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            @if(isset($formObj->security_id) && !empty($formObj->security_id))
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Security<span class="required">*</span></label>
                                    {!! Form::select('security_id',[''=>'Search Graph']+$graphs,null,['class' => 'form-control graphs']) !!}
                                </div>
                            </div>
                            @endif
                            @if(isset($formObj->post_id) && !empty($formObj->post_id))
                            <div class="row" id="post_id">
                                <div class="col-md-12">
                                    <label class="control-label">Post Name<span class="required">*</span></label>
                                    {!! Form::select('post_id',[''=>'Search Post']+ $posts,null,['class' => 'form-control posts']) !!}
                                </div>
                            </div>
                            @endif
                            
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Country<span class="required">*</span></label>
                                    {!! Form::select('country_id',[''=>'Select Country']+ $countries,null,['class' => 'country form-control']) !!}
                                </div>
                                <div class="col-md-6" style="display: none;">
                                    <label class="control-label">Graph Type<span class="required">*</span></label>
                                    {!! Form::select('graph_type',['line'=>'Line Graph'],null,['class' => 'form-control', 'data-required' => true,]) !!}
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Order<span class="required">*</span></label>
                                    {!! Form::number('order',null,['class' => 'form-control', 'data-required' => true,]) !!}
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Status<span class="required">*</span></label>
                                    {!! Form::select('status',[''=>'Select Status',1=>'Active',0=>'Inactive'],null,['class' => 'form-control', 'data-required' => true,]) !!}
                                </div>
                            </div>
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

        $('#slider_id').on('change',function(){
            
            $('#AjaxLoaderDiv').fadeIn('slow');
            var slider_val = $('#slider_id').val();
            if(slider_val == 'post'){
                $('#post_id').show();
                $('#graph_id').hide();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            if(slider_val == 'graph'){
                $('#post_id').hide();
                $('#graph_id').show();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            if(slider_val == ''){
                $('#post_id').hide();
                $('#graph_id').hide();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
        });

        $(".country").select2({
                placeholder: "Search Country",
                allowClear: true,
                minimumInputLength: 2,
                width: null
        });
        $(".graphs").select2({
                placeholder: "Search Graph",
                allowClear: true,
                minimumInputLength: 2,
                width: null
        });
        $(".posts").select2({
                placeholder: "Search Post",
                allowClear: true,
                minimumInputLength: 2,
                width: null
        });
        $('#main-frm').submit(function () {
            
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

