@extends('admin.layouts.app')


@section('content')

<div class="page-content">
    <div class="container">
        <div class="row autoResizeHeight">
            <div class="col-md-12">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-user"></i>
                           {{ $page_title }}
                        </div>
                        <a class="btn btn-default pull-right btn-sm mTop5" href="{{ $list_url }}">Back</a>
                    </div>
                    <div class="portlet-body">
                        <div class="form-body">
                           {!! Form::model($formObj,['method' => $method,'files' => true, 'route' => [$action_url,$action_params],'class' => 'sky-form form form-group', 'id' => 'main-frm1']) !!} 
                           
                                <div class="row">                                
                                    <div class="col-md-4">
                                        <label class="control-label">Use Type <span class="required">*</span></label>
                                        {!! Form::select('user_type_id', [''=>'Select User Type'] + $userTypeList, null, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Name <span class="required">*</span></label>                                        
                                        {!! Form::text('name',null,['class' => 'form-control', 'data-required' => true]) !!}
                                    </div>
									 <div class="col-md-4">
                                        <label class="control-label">Email <span class="required">*</span></label>                                        
                                        {!! Form::text('email',null,['class' => 'form-control', 'data-required' => true, 'data-type' => 'email']) !!}
                                    </div>
                                </div>                                    
                                <div class="clearfix">&nbsp;</div>
                                @if($show_password == 1 && isset($show_password))

                                <div class="row">
									<div class="col-md-6">
                                        <label class="control-label">Password <span class="required">*</span></label>                                        
                                        {!! Form::password('password',['class' => 'form-control','data-required' => 'true']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Confirm Password <span class="required">*</span></label>                                        
                                        {!! Form::password('confirm_password',['class' => 'form-control','data-required' => 'true']) !!}
                                    </div>   
                                </div>
                                @endif
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
        $('#main-frm1').submit(function () {
            
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

