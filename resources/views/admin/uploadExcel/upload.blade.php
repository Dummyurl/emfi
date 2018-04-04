
@extends('admin.layouts.app')

@section('breadcrumb')


@stop
<?php
$today = date('Y-m-d');
?>

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
                    </div>

                    <div class="portlet-body">
                        <div class="form-body">
                            {!! Form::open(['method' => "POST",'files' => true, 'route' => ['validate'],'class' => 'sky-form form form-group', 'id' => 'main-frm']) !!}
                                <div class="row ">
                                    <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Uploaded Date<span class="required">*</span></label>
                                        {!! Form::text('uploaded_date',null, ['class' => 'form-control upload_date', 'data-required' => true]) !!}
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Upload<span class="required">*</span></label>
                                        {!! Form::file('excelToUpload', ['class' => 'form-control', 'data-required' => true]) !!}
                                    </div>
                                    <input type="submit" value="Upload" class="btn btn-success" />

                                    </div>
                                <!-- <div class="col-md-3 pull-right text-right">
                                    <a href="{{ asset('file/Sample.csv') }}" class="btn btn-primary">
                                        Sample File DownLoad</a>
                                </div> -->

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
        $('#main-frm').submit(function (e) {
            e.preventDefault();
            if ($(this).parsley('isValid'))
            {

                $('#AjaxLoaderDiv').fadeIn('slow');
                $.ajax({
                    method: "POST",
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

<script type="text/javascript">
$(document).ready(function () {
        $(".upload_date").datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true,
            yearRange: '1900:2050',
            showButtonPanel: false,
            maxDate: 0,
            onClose: function (selectedDate) {
            }
        }).datepicker("setDate", new Date());
});
</script>
@endsection
