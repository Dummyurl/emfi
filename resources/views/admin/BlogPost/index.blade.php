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
                                   <th width="15%">ID</th>
                                   <th width="20%">Category</th>                                   
                                   <th width="25%">Title</th>                                   
                                   <th width="20%">Created AT</th>
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
                    data.search_id = $("#search-frm input[name='search_id']").val();
                    data.search_category = $("#search-frm select[name='search_category']").val();
                    data.search_text = $("#search-frm input[name='search_text']").val();
                   
                 }
            },            
            "order": [[ 0, "desc" ]],    
            columns: [
                { data: 'id', name: 'id' },
                { data: 'category_title', name: 'blog_categories.title' },                
                { data: 'title', name: 'title' },                
                { data: 'created_at', name: 'created_at', searchable: false},
                { data: 'action', orderable: false, searchable: false}             
            ]
        });  

    });

    </script>

@endsection

