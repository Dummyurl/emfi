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
                                <div class="col-md-12">
                                    <label class="control-label">Graph Name<span class="required">*</span></label>
                                    {!! Form::text('graph_name',null,['class' => 'form-control', 'data-required' => true,'placeholder'=>'Enter Graph Name']) !!}
                                </div> 
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">CMS Page<span class="required">*</span></label>
                                    {!! Form::select('cms_page_id', [''=>'Select CMS Page'] + $pages, null, ['class' => 'form-control']) !!}
                                </div> 
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Graph Position<span class="required">*</span></label>
                                    {!! Form::select('position', [''=>'Select Graph Position'] + $positions, null, ['class' => 'form-control']) !!}
                                </div>                                
                            </div> 
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Graph Location<span class="required">*</span></label>
                                    {!! Form::select('location', [''=>'Select Location'] + $cities, null, ['class' => 'form-control']) !!}
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


