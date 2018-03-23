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
                            <i class="fa fa-user"></i>
                            {{ $page_title }}
                        </div>
                        <a class="btn btn-default pull-right btn-sm mTop5" href="{{ $list_url}}">Back</a>
                    </div>
                    <div class="portlet-body">
                        <div class="form-body">
                             {!! Form::model($formObj,['method' => 'POST','files' => true, 'route' => [$action_url,$action_params],'class' => 'sky-form form form-group', 'id' => 'main-frm']) !!} 

                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">CUSIP<span class="required">*</span></label>
                                    {!! Form::text('CUSIP',null,['class' => 'form-control', 'data-required' => true]) !!}
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Market<span class="required">*</span></label>
                                    {!! Form::select('market_id',[''=>'Select Market']+$markets,null,['class' => 'form-control', 'data-required' => true,'id'=>'market']) !!}
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Country<span class="required">*</span></label>
                                    {!! Form::select('country_id',[''=>'Select Country']+$countries,null,['class' => 'form-control', 'data-required' => true,'id'=>'country']) !!}
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">                                
                                <div class="col-md-6">
                                    <label class="control-label">Ticker<span class="required">*</span></label>
                                    {!! Form::text('ticker',null,['class' => 'form-control', 'data-required' => true]) !!}
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">CPN<span class="required">*</span></label>
                                    {!! Form::text('cpn',null,['class' => 'form-control', 'data-required' => true]) !!}
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Benchmark<span class="required">*</span></label>
                                    {!! Form::text('benchmark',null,['class' => 'form-control', 'data-required' => true]) !!}
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Benchmark Family<span class="required">*</span></label>
                                    {!! Form::text('benchmark_family',null,['class' => 'form-control', 'data-required' => false]) !!}
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Security Name<span class="required">*</span></label>
                                    {!! Form::text('security_name',null,['class' => 'form-control', 'data-required' => true]) !!}
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Maturity Date<span class="required">*</span></label>
                                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                                        {!! Form::text('maturity_date',null,['class' => 'form-control pick_date', 'data-required' => true]) !!}
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Dur Adj Mid<span class="required">*</span></label>
                                    {!! Form::text('dur_adj_mid',null,['class' => 'form-control', 'data-required' => true]) !!}
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Bid Price<span class="required">*</span></label>
                                    {!! Form::text('bid_price',null,['class' => 'form-control', 'data-required' => true]) !!}
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Ask Price<span class="required">*</span></label>
                                    {!! Form::text('ask_price',null,['class' => 'form-control', 'data-required' => true]) !!}
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Last Price<span class="required">*</span></label>
                                    {!! Form::text('last_price',null,['class' => 'form-control', 'data-required' => true]) !!}
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Low Price<span class="required">*</span></label>
                                    {!! Form::text('low_price',null,['class' => 'form-control', 'data-required' => true]) !!}
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">High Price<span class="required">*</span></label>
                                    {!! Form::text('high_price',null,['class' => 'form-control', 'data-required' => true]) !!}
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Yld Ytm Mid<span class="required">*</span></label>
                                    {!! Form::text('yld_ytm_mid',null,['class' => 'form-control', 'data-required' => true]) !!}
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Z Sprd Mid<span class="required">*</span></label>
                                    {!! Form::text('z_sprd_mid',null,['class' => 'form-control', 'data-required' => true]) !!}
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Net Change<span class="required">*</span></label>
                                    {!! Form::text('net_change',null,['class' => 'form-control', 'data-required' => true]) !!}
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Percentage Change<span class="required">*</span></label>
                                    {!! Form::text('percentage_change',null,['class' => 'form-control', 'data-required' => true]) !!}
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
    $(document).ready(function () {
        $("#country").select2({
                placeholder: "Search Country",
                allowClear: true,
                minimumInputLength: 2,
                width: null
        });
        $("#market").select2({
                placeholder: "Search Market",
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
                            window.location = '{{ $list_url}}';    
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


