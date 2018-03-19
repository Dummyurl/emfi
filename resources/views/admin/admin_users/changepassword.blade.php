@extends('admin.layouts.app')
<style>
    .page-header-top{
        display: none !important;
    }
    .page-header-menu{
        display:none !important;
    }
    .page-footer{
        display:none !important;
    }
    .phpdebugbar{
        display: none !important;
    }
</style>
<?php
$action_url = 'admin/adminusers/changepassword/';
?>
@section('content')
<div class="page-content">
    <div class="container">
        <div class="row autoResizeHeight">
        <br/>
            <div class="col-md-12">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-user"></i>
                            Change Password
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="form-body">
                        {!! Form::model($formObj, ['method' => 'PUT','files' => true, 'url' =>$action_url.$formObj->id,'class' => 'sky-form form form-group', 'id' => 'main-frm']) !!}
                                <div class="row">                                
                                    <div class="col-md-4">
                                        <label class="control-label">New Password <span class="required">*</span></label>                                        
                                        {!! Form::password('new_password',['class' => 'form-control', 'data-required' => true]) !!}
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Confirm Password <span class="required">*</span></label>            
                                        {!! Form::password('new_password_confirmation',['class' => 'form-control', 'data-required' => true,]) !!}
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
                    url: $(this).attr("password"),
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
                            setTimeout( function() {parent.$.fancybox.close();},6000);
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


