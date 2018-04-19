@extends('admin.layouts.app')

@section('breadcrumb')

@stop

@section('content')

<div class="page-content">
    <div class="container">
        <div class="row autoResizeHeight">
            <div class="col-md-12">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-user"></i>
                            {{ $page_title }}
                        </div>
                        <a class="btn btn-default pull-right btn-sm mTop5" href="{{ $list_url }}">Back</a>
                    </div>
                    <div class="portlet-body">
                        <div class="form-body">
                             {!! Form::model($formObj,['method' => $method,'files' => true, 'route' => [$action_url,$action_params],'class' => 'sky-form form form-group', 'id' => 'main-frm']) !!} 
                             <div class="clearfix">&nbsp;</div>
                            <div class="row">
                             @foreach($languages as $lng => $val)
                             <?php 
                                $title = null;
                                if(isset($formObj->id) && !empty($formObj->id)){
                                $trans =  \App\Models\CountryTranslation::where('locale',$lng)->where('country_id',$formObj->id)->first();
                                if($trans)
                                {
                                    $title = $trans->country_name;
                                }
                            }?>
                                <div class="col-md-6">
                                    <label for="" class="control-label">Title [{{ $val }}] <span class="required">*</span>
                                    </label>
                                    {!! Form::text('title['.$lng.']',$title,['class' => 'form-control','Placeholder'=>'Enter Title for '.$val]) !!}
                                </div>
                            @endforeach
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Country Code<span class="required">*</span></label>
                                    {!! Form::text('country_code',null,['class' => 'form-control', 'data-required' => true]) !!}
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Type<span class="required">*</span></label>
                                    {!! Form::select('country_type',[1=>DEVELOPED_COUNTRY, 2=>EMERGING_COUNTRY],null,['class' => 'form-control', 'data-required' => true,]) !!}
                                </div>
                            </div>                                  
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="submit" value="Save" class="btn btn-success pull-right" />
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>                 
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('#main-frm').submit(function () {

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


