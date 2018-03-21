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
            <h4 class="modal-title">Edit Office Template Image</h4>
          </div>
          <div class="modal-body">
                {!! Form::open(['method' => 'PUT','url' => '', 'files' => true,'class' => 'sky-form form form-group main-frm']) !!}
                <input type="hidden" name="office_id" value="{{$row->office_id}}" />
                <div class="form-group">
                    <label for="">Template</label>
                    <select class="form-control" name="template_id">
                        @foreach($templates as $t)
                            <option value="{{ $t->id }}" {{ $t->id == $row->template_id ? 'selected="selected"' : '' }}>{{$t->title}}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="edit_id" value="" />
                <div class="form-group">
                    <label for="">Image</label>
                    <input type="file" name="image" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="">Description</label>
                    <textarea class="form-control" name="description" rows="5">{!! $row->description !!}</textarea>
                    <div class="alert alert-info">
                        <p>Note: You can add {office_name},{city_state} place holder in description.for retrive dynamic value of office name and office address</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Text Alignment</label>
                    {!! Form::select('text_alignment', $align_options, $row->text_alignment, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">CSS_Code</label>
                    <textarea class="form-control" name="style_code" rows="3">{{$row->style_code}}</textarea>
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
                { data: 'action', orderable: false, searchable: false}
            ]
        });
    });
    </script>
@endsection
