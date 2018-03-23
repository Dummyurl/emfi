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
								<th width="10%">ID</th>
                               <th width="22%">Company Name</th>
                               <th width="15%">Market Type</th>
							   <th width="5%">Benchmark</th>
                               <th width="28%">Security Name</th>
                               <th width="10%" data-orderable="false">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
	<div id="editForm" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Security Benchmark</h4>
          </div>
          <div class="modal-body">
                {!! Form::open(['method' => 'post' , 'files' => true,'class' => 'sky-form form form-group main-frm']) !!}
                <input type="hidden" name="security_id" value="" id="security_id" />
                <div class="form-group">
                    <label for="">Add New Benchmark</label>
                    <input type="text" class="form-control" name="new_benchmark" id="new_benchmark">
                </div>
				<div class="form-group">
					<label for="">Select Benchmark</label>
					{!! Form::select('select_benchmark', ['0'=>'Select Benchmark'] + $benchmark_family_list, null, ['class' => 'form-control', 'id' => 'select_benchmark']) !!}
				</div>
				<div class="form-group ">
					<label for="" class="mt-checkbox">
					</label>
				</div>
				<div class="form-group">
                    <div class="col-md-9">
                        <div class="mt-checkbox-inline">
                            <label class="mt-checkbox">
								{!! Form::checkbox('set_benchmark', null, false, ['class' => 'form-control', 'id' => 'check']) !!}Benchmark Set
								<span></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success pull-right">Update</button>
                </div>
                {!! Form::close() !!}
                <div class="clearfix"></div>
                <div class="clearfix">&nbsp;</div>
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
	function edit(id, benchmark, flag) {

		var security = id;
		flag = parseInt(flag);
		$('.main-frm #security_id').attr('value', id);
		$('#select_benchmark').val(benchmark);
		$('#security_id').val(id);
		if (benchmark == 0) {
			$('#new_benchmark').val('');
			$('#new_benchmark').attr('disabled' , false);
		} else {
			$('#new_benchmark').val('');
			$('#new_benchmark').attr('disabled' , true);
		}

		if(flag)
		$("#check").prop('checked', true);
		else
		$("#check").prop('checked', false);

		$('#editForm').modal();
	}

    $(document).ready(function(){


        $("#search-frm").submit(function(){
            oTableCustom.draw();
            return false;
        });

		$("#select_benchmark").on('change', function () {
			if ($(this).val() != "0") {
				$('#new_benchmark').val('');
				$('#new_benchmark').attr('disabled' , true);
			}
			else {
				$('#new_benchmark').removeAttr('disabled');
			}
		});

		$('.main-frm').submit(function ()
		{
			if ($(this).parsley('isValid'))
			{
				$('#AjaxLoaderDiv').fadeIn('slow');
				$.ajax({
					type: "POST",
					url: "{{ url('admin/editsecurity' ) }}" + '/'+ $("#security_id").val(),
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
							window.location.reload();
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
                    data.search_market = $("#search-frm select[name='search_market']").val();
                }
            },
            "order": [[ 0, "asc" ]],
            columns: [
				{ data :'id' , name : 'id' },
                { data: 'CUSIP', name: 'CUSIP' },
                { data: 'market_name', name: 'market_type.id'},
                { data: 'benchmark_family', name: 'benchmark_family' },
                { data: 'security_name', name: 'security_name' },
                { data: 'action', orderable: false, searchable: false}
            ]
        });
    });
    </script>
@endsection
