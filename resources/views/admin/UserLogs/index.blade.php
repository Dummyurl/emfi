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
                </div>
                <div class="portlet-body">                    
                    <table class="table table-bordered table-striped table-condensed flip-content" id="server-side-datatables">
                        <thead>
                            <tr>
                               <th width="5%">ID</th>                                   
                               <th width="15%">Username</th>                           
                               <th width="10%">Action Name</th>                           
                               <th width="10%">Action Val</th>                           
                               <th width="10%">Ip Address</th>                           
                               <th width="20%">Remarks</th>                           
                               <th width="20%">Log Date</th>                           
                               
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
                    data.search_userid = $("#search-frm select[name='search_userid']").val();
                    data.search_actionid = $("#search-frm select[name='search_actionid']").val();
                    data.search_action_value = $("#search-frm input[name='search_action_value']").val();
                    data.search_remark = $("#search-frm input[name='search_remark']").val();
                    data.search_address = $("#search-frm input[name='search_address']").val();
                    data.search_ids = $("#search-frm input[name='search_ids']").val();
                    data.search_admin_name = $("#search-frm input[name='search_admin_name']").val();
                    data.search_distinct_user = $("#search-frm input[name='search_distinct_user']").val();
                }
            },            
            "order": [[ 0, "desc" ]],    
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'userid' },                                              
                { data: 'actionid', name: 'actionid' },                                              
                { data: 'actionvalue', name: 'actionvalue' },    
                { data: 'remark', name: 'remark' },                                              
                { data: 'ipaddress', name: 'ipaddress' },
                { data: 'created_at', name: 'created_at' }
            ]
        });        

    });
    </script>

@endsection




