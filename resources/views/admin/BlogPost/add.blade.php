@extends('admin.layouts.app')

@section('breadcrumb')


@stop

@section('content')

<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-list"></i>
                            {{ $page_title }}
                        </div>
                        <a class="btn btn-default pull-right btn-sm mTop5" href="{{ $list_url }}">Back</a>
                    </div>
                    <div class="portlet-body">
                        <div class="form-body">                            
                            {!! Form::model($formObj,['method' => $method,'files' => true, 'route' => [$action_url,$action_params],'class' => 'sky-form form form-group', 'id' => 'main-frm']) !!} 
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">Post Category <span class="required">*</span></label>
                                        {!! Form::select('category_id',$categories, null,['class' => 'form-control', 'data-required' => true]) !!}
                                    </div>   
                                    <div class="col-md-4">
                                        <label class="control-label">Title <span class="required">*</span></label>                                        
                                        {!! Form::text('title',null,['class' => 'form-control', 'data-required' => true]) !!}
                                    </div>   
                                    <div class="col-md-4">
                                        <label class="control-label">Active <span class="required">*</span></label>                                        
                                        {!! Form::select('status',[1 => "Yes",0 => "No"],null,['class' => 'form-control', 'data-required' => true]) !!}
                                    </div>   
                                </div>

                                <div class="clearfix">&nbsp;</div> 
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label class="control-label">Short Description <span class="required">*</span></label>
                                        {!!Form::textarea('short_description',null,['class'=>'form-control','rows'=>'3','placeholder'=>'Enter Description', 'data-required' => true])!!}
                                    </div>
                                </div>

                                <div class="clearfix">&nbsp;</div> 
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label class="control-label">Content <span class="required">*</span></label>
                                        {!!Form::textarea('content',null,['class'=>'form-control','rows'=>'6','placeholder'=>'Enter Content', 'data-required' => true])!!}
                                    </div>
                                </div>                      
                                <div class="clearfix">&nbsp;</div>
                                                         
                                <div class="clearfix">&nbsp;</div>

                                <div class="portlet">
                                            <div class="portlet-title">

                                                <div class="note note-info">
                                                        <h4>Tags</h4>
                                                </div>
                                                <div class="tools abs">
                                                    <a href="javascript:;" class="collapse"> </a>
                                                </div>

                                            </div>
                                            <div class="portlet-body" style="display: block;">
                                                <select class="select2_tags" multiple="multiple" name="tags[]">
                                                   
                                                @foreach($tags as $row)
                                                        <option {{ in_array($row->id, $list_tags) ? 'selected':''}} value="{{ $row->id }}">{{ $row->title }}</option>
                                                @endforeach

                                                </select>                                            
                                            </div>
                                         </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="submit" value="{{ $buttonText}}" class="btn btn-success pull-right" />
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

@section('styles')

    <link href="{{ asset("/themes/admin/assets")}}/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/themes/admin/assets")}}/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')

<script src="{{ asset("/themes/admin/assets")}}/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="{{ asset("/themes/admin/assets")}}/pages/scripts/components-select2.min.js" type="text/javascript"></script>


<script type="text/javascript">
    $(document).ready(function () {

        $(".select2_tags").select2({
                placeholder: "Search Tags",
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


