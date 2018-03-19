<div class="btn-group">
@if($isEdit)
<a href="{{ route($currentRoute.'.edit',['id' => $row->id]) }}" class="btn btn-xs btn-primary" title="edit">
    <i class="fa fa-edit"></i>
</a>         
@endif

@if($isDelete)
<a data-id="{{ $row->id }}" href="{{ route($currentRoute.'.destroy',['id' => $row->id]) }}" class="btn btn-xs btn-danger btn-delete-record" title="delete">
    <i class="fa fa-trash-o"></i>
</a>          
@endif

@if($btnChangePass)
<a href="{{ url('admin/admin-users/changepassword/'.$row->id) }}" class="btn btn-xs btn-success fancybox_iframe" title="Change Password">
    <i class="fa fa-key" aria-hidden="true"></i>
</a>
@endif  
</div>