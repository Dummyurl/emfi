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
                            <i class="fa fa-file"></i>
                            {{ $page_title }}
                        </div>
                        <a class="btn btn-default pull-right btn-sm mTop5" href="{{ $list_url }}">Back</a>
                    </div>
                    <div class="portlet-body">
                        <div class="form-body">
                             {!! Form::model($formObj,['method' => $method,'files' => true, 'route' => [$action_url,$action_params],'class' => 'sky-form form form-group', 'id' => 'main-frm']) !!} 

                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Title<span class="required">*</span></label>
                                    {!! Form::text('title',null,['class' => 'form-control', 'data-required' => true]) !!}
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Page Title<span class="required">*</span></label>
                                    {!! Form::text('page_title',null,['class' => 'form-control', 'data-required' => true]) !!}
                                </div> 
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Meta Title<span class="required">*</span></label>
                                    {!! Form::text('meta_title',null,['class' => 'form-control', 'data-required' => true]) !!}
                                </div> 
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Meta Description<span class="required">*</span></label>
                                    {!! Form::textarea('meta_description',null,['class' => 'form-control','data-required' => true, 'rows'=>'3']) !!}
                                </div>                                
                            </div> 
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">                                
                                <div class="col-md-12">
                                    <label class="control-label">Short Description<span class="required">*</span></label>
                                    {!! Form::textarea('short_description',null,['class' => 'form-control ckeditor','data-required' => true,'rows'=>'5']) !!}
                                </div> 
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">                            
                                <div class="col-md-12">  
                                    <label class="control-label">Description<span class="required">*</span></label>
                                    {!! Form::textarea('description',null,['class' => 'form-control ckeditor','data-required' => true,'rows'=>'8']) !!}
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
    $(document).ready(function () {
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


