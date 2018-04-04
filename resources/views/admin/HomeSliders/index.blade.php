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
    $(document).ready(function(){

        $('.country').on('change',function(){
            var country_val = $('.country').val();
            if(country_val != '')
            {
                $('#graph_type_row').show();
            }else{
                $('#graph_type_row').hide();
                $('.down_content').hide();
            }
        });

        $('#graph_type_id').on('change',function(){

            $('#AjaxLoaderDiv').fadeIn('slow');
            var graph_val = $('#graph_type_id').val();
            if(graph_val == 'line'){
                $('.down_content').show();
                $('#security_id').show();
                $('#security_id_val').attr('disabled',false);
                $('#yield_curve_div').hide();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == 'yield_curve'){
                $('.down_content').show();
                $('#security_id').hide();
                $('#security_id_val').attr('disabled',true);
                $('#yield_curve_div').show();
                $('#AjaxLoaderDiv').fadeOut('slow');
            }
            else if(graph_val == ''){
                $('.down_content').show();
                $('#security_id').hide();
                $('#security_id_val').attr('disabled',true);
                $('#yield_curve_div').hide();
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
@endsection
