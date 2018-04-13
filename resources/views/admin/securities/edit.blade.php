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
                             {!! Form::model($formObj,['method' => $method,'files' => true, 'route' => [$action_url,$action_params],'class' => 'sky-form form form-group', 'id' => 'main-frm']) !!} 
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">CUSIP<span class="required">*</span></label>
                                        {!! Form::text('CUSIP',null,['class' => 'form-control', 'data-required' => true]) !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Market<span class="required">*</span></label>
                                        {!! Form::select('market_id',[''=>'Select Market']+$markets,null,['class' => 'form-control', 'data-required' => true,'id'=>'market']) !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Country<span class="required">*</span></label>
                                        {!! Form::select('country_id',[''=>'Select Country']+$countries,null,['class' => 'form-control', 'data-required' => true,'id'=>'country']) !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                         <label class="control-label">Ticker<span class="required">*</span></label>
                                        {!! Form::text('ticker',null,['class' => 'form-control', 'data-required' => true]) !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">CPN<span class="required">*</span></label>
                                        {!! Form::text('cpn',null,['class' => 'form-control', 'data-required' => true]) !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Security Name<span class="required">*</span></label>
                                        {!! Form::text('security_name',null,['class' => 'form-control', 'data-required' => true]) !!}
                                    </div>
                                </div>
                                <div class="col-md-12" id="maturity_date_div" style="display: none;">
                                    <div class="form-group">
                                        <label class="control-label">Maturity Date<span class="required">*</span></label>
                                        <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                                            {!! Form::text('maturity_date',null,['class' => 'form-control pick_date', 'data-required' => false]) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mt-checkbox-inline">
                                        <label class="mt-checkbox">
                                            {!! Form::checkbox('benchmark',1, $formObj->benchmark,['class' => 'form-control','id'=>'check_id']) !!}Enable Benchmark
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <div id="benchmark_div">
                            <!--<div class="clearfix">&nbsp;</div>
                             <div class="note note-info">
                                <div class="row">
                                    <div class="col-md-10" style="padding-left: 30px;">
                                        <h4>Benchmark Details</h4>
                                    </div>   
                                </div>
                            </div> -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Benchmark</label>
                                        {!! Form::select('benchmark_family', ['0'=>'Other Benchmark'] + $benchmark_family_list, null, ['class' => 'form-control', 'id' => 'select_benchmark']) !!}
                                    </div>
                                </div>
                                <div class="col-md-12" id="new_benchmark_div" style="display: none;">
                                    <div class="form-group">
                                        <label class="control-label">Add Benchmark</label>
                                        {!! Form::text('new_benchmark_family',null,['class' => 'form-control', 'data-required' => false,'id'=>'new_benchmark']) !!}
                                    </div>
                                </div>
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
        var formObj_market = '{{ $formObj->market_id}}';
        var benchmark = '{{ $formObj->benchmark}}';
        var benchmark_family = '{{ $formObj->benchmark_family}}';

        if(formObj_market != '' && formObj_market == 5)
        {
            $('#maturity_date_div').show();
        }
        if(benchmark != 1)
        {
            $("#benchmark_div").hide();
        }
        /*$("#country").select2({
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
        });*/
        $('#market').on('change',function(){
            var market_val = $('#market').val();

            if(market_val == 5)
            {
                $('#maturity_date_div').show();
            }else{
                $('#maturity_date_div').hide();
            }

        });
        $("#check_id").click(function () {
            if ($(this).is(":checked")) {
                $("#benchmark_div").show();
                var bench = $("#select_benchmark").val();
                if(bench == 0)
                {
                    $('#new_benchmark').attr('disabled' , false);
                    $('#new_benchmark_div').show();
                }
            } else {
                $("#benchmark_div").hide();
            }
        });

        var bench = $("#select_benchmark").val();
        if(bench != '0'){
            $('#new_benchmark').attr('disabled' , true);
        }
        $("#select_benchmark").on('change', function () {
            if ($(this).val() != "0") {
                $('#new_benchmark').val('');
                $('#new_benchmark').attr('disabled' , true);
                $('#new_benchmark_div').hide();
            }
            else {
                $('#new_benchmark').attr('disabled' , false);
                $('#new_benchmark_div').show();
            }
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


