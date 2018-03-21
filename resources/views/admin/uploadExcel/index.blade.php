@extends('admin.layouts.app')

@section('content')

<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content">
    <div class="container">

        <div class="col-md-12">

            @include("admin.uploadExcel.search")

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
                               <th width="22%">Company Name</th>
                               <th width="15%">Market Type</th>
							   <th width="5%">Benchmark</th>
                               <th width="28%">Security Name</th>
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
	function edit() {

	}

    $(document).ready(function(){


        $("#search-frm").submit(function(){
            oTableCustom.draw();
            return false;
        });




        $.fn.dataTableExt.sErrMode = 'throw';

        var oTableCustom = $('#server-side-datatables').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                "url": "{!! route('datasecurity') !!}",
                "data": function ( data )
                {
                    data.search_cusip = $("#search-frm input[name='search_cusip']").val();
                    data.market_type = $("#search-frm select[name='market_type']").val();
                }
            },
            "order": [[ 0, "desc" ]],
            columns: [
                { data: 'CUSIP', name: 'CUSIP' },
                { data: 'market_name', name: 'market_type.id'},
                { data: 'benchmark_family', name: 'benchmark_family' },
                { data: 'security_name', name: 'security_name' },
            ]
        });
    });
    </script>
@endsection
