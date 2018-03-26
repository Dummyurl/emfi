@extends('admin.layouts.app')


@section('content')

<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content">
    <div class="container">

        <div class="col-md-12">

            @include($moduleViewName.".search")

            <div class="clearfix"></div>
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-list"></i>{{ $page_title }}
                    </div>

                    @if($btnAdd)
                        <a class="btn btn-default pull-right btn-sm mTop5" href="{{ $add_url }}">Add New</a>
                    @endif

                </div>
                <div class="portlet-body">
                        <table class="table table-bordered table-striped table-condensed flip-content" id="server-side-datatables">
                            <thead>
                                <tr>
                                   <th width="10%">ID</th>
                                   <th width="30%">Graph Name</th>
                                   <th width="30%">Post Name</th>
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

		$(".country").select2({
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
                    data.search_post = $("#search-frm input[name='search_post']").val();
                    data.search_graph = $("#search-frm input[name='search_graph']").val();
                    data.search_country = $("#search-frm select[name='search_country']").val();
                    data.search_status = $("#search-frm select[name='search_status']").val();
                }
            },
            "order": [[ 0, "desc" ]],
            columns: [
                { data: 'id', name: 'id' },
                { data: 'graph', name: '{{TBL_SECURITY}}.CUSIP' },
                { data: 'post_title', name: 'post_title' },
                { data: 'country', name: '{{TBL_COUNTRY}}.title' },
                { data: 'status', name: 'status' },
				{ data : 'order' , name : 'order' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', orderable: false, searchable: false}
            ]
        });
    });
    </script>
@endsection
