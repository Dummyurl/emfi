@extends('admin.layouts.app')


@section('content')

<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content">
    <div class="container">

        <div class="col-md-12">

            @include($moduleViewName.".add")

            @include($moduleViewName.".search")

            <div class="clearfix"></div>
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-list"></i>{{ $page_title }}
                    </div>

                    @if(false)
                        <a class="btn btn-default pull-right btn-sm mTop5" href="{{ $add_url }}">Add New</a>
                    @endif

                </div>
                <div class="portlet-body">
                        <table class="table table-bordered table-striped table-condensed flip-content" id="server-side-datatables">
                            <thead>
                                <tr>
                                   <th width="10%">ID</th>
                                   <th width="30%">Security</th>
                                   <th width="30%">Post</th>
                                   <th width="10%">Country</th>
                                   <th width="5%">Status</th>
								   <th width="5">Order</th>
                                   <th width="20%">Created At</th>
                                   <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('styles')

@endsection

@section('scripts')
    <script type="text/javascript">


    $(document).ready(function(){


        $("#search-frm").submit(function(){
            oTableCustom.draw();
            return false;
        });

		$(".countrys").select2({
			placeholder: "Search Country",
			allowClear: true,
			minimumInputLength: 2,
			width: null
		});

        $.fn.dataTableExt.sErrMode = 'throw';

        var oTableCustom = $('#server-side-datatables').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                "url": "{!! route($moduleRouteText.'.data') !!}",
                "data": function ( data )
                {
                    data.search_start_date = $("#search-frm input[name='search_start_date']").val();
                    data.search_end_date = $("#search-frm input[name='search_end_date']").val();
                    data.search_graph = $("#search-frm input[name='search_graph']").val();
                    data.search_country = $("#search-frm select[name='search_country']").val();
                    data.search_status = $("#search-frm select[name='search_status']").val();
                }
            },
            "order": [[ 0, "desc" ]],
            columns: [
                { data: 'id', name: 'id' },
                { data: 'graph', name: '{{TBL_SECURITY}}.CUSIP' },
                { data: 'en_title', name: 'id' },
                { data: 'country', name: '{{TBL_COUNTRY}}.title' },
                { data: 'status', name: 'status' },
				{ data : 'order' , name : 'order' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', orderable: false, searchable: false},
            ]
        });
    });
    </script>
    <script type="text/javascript">
        function getBanchmarkViaCountry(country_id)
                        {
            $('#AjaxLoaderDiv').fadeIn('slow');
            var country_val = country_id;
                 $.ajax({
                        type: "GET",
                        url: "/admin/getcountries/banchmark/",
                        data: {country_id:country_val},
                        success: function (result)
                        {
                            var $country = $('#option_banchmark');
                            $country.empty();
                            $country.append('<option>Select Banchmark</option>');
                            $.each(result, function(k, v) {
                                if(v.country_id != country_val){
                                    $country.append('<option value="' + v.country_id + '">' + v.country_title + '</option>');
                                }
                            });
                    $('#AjaxLoaderDiv').fadeOut('slow');
                            $country.change();
                        },
                        error: function (error) {
                        }
                    });
            }

        function getBanchmarkViaSecurity(security_id)
        {
            $('#AjaxLoaderDiv').fadeIn('slow');
                $.ajax({
                    type: "GET",
                    url: "/admin/getsecurities/banchmark/",
                    data: {security_id:security_id},
                    success: function (result)
                    {
                        if(result.price == 5){
                            $('#prices_div').show();
                        }else if(result.price == 1){
                            $('#prices_div').show();
                            var $country = $('#option_price_id');
                            $country.empty();
                            $country.append('<option value="">Select Option</option>');
                            $country.append('<option value="1">Price</option>');
                            $country.append('<option value="2">Yield</option>');
                            $country.change();
                        }else{
                            $('#prices_div').hide();
                        }
                        var $country = $('#option_banchmark');
                        $country.empty();
                        $country.append('<option>Select Banchmark</option>');
                        $.each(result.banchmark, function(k, v) {
                            $country.append('<option value="' + v.id + '">' + v.title + '</option>');
                        });
                    $('#AjaxLoaderDiv').fadeOut('slow');
                    $country.change();

                },
                error: function (error) {
                }
            });
        }
        function getSecurity(country_id)
        {
            $('#AjaxLoaderDiv').fadeIn('slow');
            var country_val = country_id;
            $.ajax({
                type: "GET",
                url: "/admin/getsecurities/",
                data: {country_id:country_val},
                success: function (result)
                {
                    var $country = $('#security_id_val');
                    $country.empty();
                    $country.append('<option>Select Security</option>');
                    $.each(result, function(k, v) {
                        $country.append('<option value="' + k + '">' + v + '</option>');
                    });
                        $country.change();
                $('#AjaxLoaderDiv').fadeOut('slow');

                    },
                    error: function (error) {
                    }
                    });
        }

        function getSecurityMarketWise(country_id)
        {
            var country_val = country_id;
            $('#AjaxLoaderDiv').fadeIn('slow');
            var market_id = [];
            market_id[0] = [1];
            market_id[1] = [5];
            $.ajax({
                    type: "GET",
                    url: "/admin/getsecurities/",
                    data: {country_id:country_val, market_id:market_id},
                    success: function (result)
                    {
                        var $country = $('#security_id_val');
                        $country.empty();
                        $country.append('<option>Select Security</option>');
                        $.each(result, function(k, v) {
                            $country.append('<option value="' + k + '">' + v + '</option>');
                        });
                        $country.change();
                        var $country = $('#option_security_val');
                        $country.empty();
                        $country.append('<option>Select Security</option>');
                        $.each(result, function(k, v) {
                            $country.append('<option value="' + k + '">' + v + '</option>');
                        });
                        $country.change();
                        $('#AjaxLoaderDiv').fadeOut('slow');
                    },
                    error: function (error) {
                    }
                });
        }
    $(document).ready(function(){

		$(".ckeditor").each(function (){
			CKEDITOR.replace($(this).attr('id'),{
				toolbarGroups: [
							{"name":"basicstyles","groups":["basicstyles"]},
							{"name":"paragraph","groups":["list","blocks"]},
							{"name":"styles","groups":["styles"]},
							{"name":"about","groups":["about"]}
						],
			});
		});

        $('.country').on('change',function(){
            $(".setValue").val($(".country option:selected").text());
            var country_val = $('.country').val();
            if(country_val != '')
            {
                $('#graph_type_row').show();
                getSecurity(country_val);
                getBanchmarkViaCountry(country_val);
            }else{
                $('#graph_type_row').hide();
                $('.down_content').hide();
            }
            $('#graph_type_id').trigger('change');
        });

        $('#security_id_val').on('change',function(){
            var security_id = $('#security_id_val').val();
            if(security_id > 0){
                getBanchmarkViaSecurity(security_id);
            }
        });

        $('#graph_type_id').on('change',function(){
            $('#AjaxLoaderDiv').fadeIn('slow');
            var graph_val = $('#graph_type_id').val();
            if(graph_val == 'line'){
                $('.down_content').show();
                $('#period_div').show();
                $('#prices_div').hide();
                $('#maturity_div').hide();
                $('#market_div').hide();
                $('#security_id').show();
                $('#security_id_val').attr('disabled',false);
                $('#rating_div').hide();
                $('#banchmark_div').show();
                $('#credit_div').hide();
                $('#option_security_div').hide();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == 'yield_curve'){
                $('.down_content').show();
                $('#period_div').show();
                $('#prices_div').show();
                $('#maturity_div').show();
                $('#market_div').hide();
                $('#security_id').hide();
                $('#security_id_val').attr('disabled',true);
                $('#rating_div').hide();
                $('#banchmark_div').show();
                $('#credit_div').hide();
                $('#option_security_div').hide();
                getBanchmarkViaCountry($('.country').val());
                $('#AjaxLoaderDiv').fadeOut('slow');
            }else if(graph_val == 'market_movers_gainers' || graph_val == 'market_movers_laggers'){
                $('.down_content').show();
                $('#period_div').hide();
                $('#prices_div').hide();
                $('#maturity_div').hide();
                $('#market_div').show();
                $('#security_id').hide();
                $('#security_id_val').attr('disabled',true);
                $('#rating_div').hide();
                $('#banchmark_div').hide();
                $('#credit_div').hide();
                $('#option_security_div').hide();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == 'market_history'){
                $('.down_content').show();
                $('#period_div').show();
                $('#prices_div').show();
                $('#maturity_div').hide();
                $('#market_div').hide();
                $('#security_id').show();
                $('#security_id_val').attr('disabled',false);
                $('#rating_div').hide();
                $('#banchmark_div').show();
                $('#credit_div').hide();
                $('#option_security_div').hide();
                getSecurity($('.country').val());
                $('#option_banchmark').empty();
                $('#option_banchmark').append('<option>Select Banchmark</option>');
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == 'differential'){
                $('.down_content').show();
                $('#period_div').show();
                $('#prices_div').show();
                $('#maturity_div').hide();
                $('#market_div').hide();
                $('#security_id').show();
                $('#security_id_val').attr('disabled',false);
                $('#rating_div').hide();
                $('#banchmark_div').hide();
                $('#credit_div').hide();
                $('#option_security_div').show();
                getSecurityMarketWise($('.country').val());
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == 'regression'){
                $('.down_content').show();
                $('#period_div').show();
                $('#prices_div').show();
                $('#maturity_div').hide();
                $('#market_div').hide();
                $('#security_id').show();
                $('#security_id_val').attr('disabled',false);
                $('#rating_div').hide();
                $('#banchmark_div').hide();
                $('#credit_div').hide();
                $('#option_security_div').show();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == 'relative_value'){
                $('.down_content').show();
                $('#period_div').show();
                $('#prices_div').show();
                $('#maturity_div').hide();
                $('#market_div').hide();
                $('#security_id').hide();
                $('#security_id_val').attr('disabled',true);
                $('#rating_div').show();
                $('#banchmark_div').hide();
                $('#credit_div').show();
                $('#option_security_div').hide();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == ''){
                $('.down_content').hide();
                $('#period_div').hide();
                $('#prices_div').hide();
                $('#maturity_div').hide();
                $('#market_div').hide();
                $('#security_id').hide();
                $('#rating_div').hide();
                $('#banchmark_div').hide();
                $('#credit_div').hide();
                $('#option_security_div').hide();
                $('#security_id_val').attr('disabled',true);
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
<script type="text/javascript">

$(document).ready(function () {
        $(".display_date").datepicker({
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
