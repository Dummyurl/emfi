@if($isEdit)
<a onclick="$('#editForm{{ $row->id}}').modal()" class="btn btn-xs btn-primary is_edit" title="edit">
    <i class="fa fa-edit"></i>
</a>         
@endif

@if($isDelete)
<a data-id="{{ $row->id }}" href="{{ route($currentRoute.'.destroy',['id' => $row->id]) }}" class="btn btn-xs btn-danger btn-delete-record" title="delete">
    <i class="fa fa-trash-o"></i>
</a>          
@endif
<div class="modal-dialog" id="editForm{{ $row->id}}">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Office Template Image</h4>
          </div>      
          <div class="modal-body">
                {!! Form::open(['method' => 'PUT','url' => 'admin/trd-template-office-images/'.$row->id, 'files' => true,'class' => 
                'sky-form form form-group main-frm']) !!} 
                <input type="hidden" name="office_id" value="" />
                <div class="form-group">
                    <label for="">Template</label>
                    <input type="text" name="">                   
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