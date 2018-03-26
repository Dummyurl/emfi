<div class="btn-group">
@if(isset($postComment) && $postComment)
<a data-id="{{ $row->id }}" href="{{ route($currentRoute.'.comment',['id' => $row->id]) }}" class="btn btn-xs btn-success" title="comment">
    <i class="fa fa-comments"></i>
</a>          
@endif

@if(isset($isEdit) && $isEdit)
<a href="{{ route($currentRoute.'.edit',['id' => $row->id]) }}" class="btn btn-xs btn-primary" title="edit">
    <i class="fa fa-edit"></i>
</a>         
@endif

@if(isset($isDelete) && $isDelete)
<a data-id="{{ $row->id }}" href="{{ route($currentRoute.'.destroy',['id' => $row->id]) }}" class="btn btn-xs btn-danger btn-delete-record" title="delete">
    <i class="fa fa-trash-o"></i>
</a>          
@endif
@if(isset($isDefault) && $isDefault)
	@if($row->default == 1)
		<a class="btn btn-xs btn-success" title="Change Deafult Sataus" href="{{ url('admin/securities?changeDefault=0&changeID='.$row->id)}}" onclick="return confirm('Are you sure ?');">
		    <i class="fa fa-check-circle-o"></i>
		</a>
	@else
		<a class="btn btn-xs btn-warning" title="Change Deafult Sataus" href="{{ url('admin/securities?changeDefault=1&changeID='.$row->id)}}" onclick="return confirm('Are you sure ?');">
		    <i class="fa fa-check-circle-o"></i>
		</a>
	@endif	   
@endif
</div>