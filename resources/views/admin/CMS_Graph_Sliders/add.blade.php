@extends('admin.layouts.app')

@section('breadcrumb')
@stop

@section('content')

<div class="page-content">
    <div class="container">
        <div class="row autoResizeHeight">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet box green">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-area-chart"></i><span id="main_title">Add CMS Graph Slider </span>
                                </div>
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#portlet_tab_1" data-toggle="tab" id="english"> English </a>
                                    </li>
                                    <li>
                                        <a href="#portlet_tab_2" data-toggle="tab" id="spanish"> Spanish </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="portlet-body">
                                
                                <div class="tab-content">
                                    <div class="tab-pane active" id="portlet_tab_1">
                                        
                                    <div class="form-body">
                                        {!! Form::model($formObj,['method' => $method,'files' => true, 'route' => [$action_url,$action_params],'class' => 'sky-form form form-group slider_form', 'id' => 'main-frm']) !!}
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label">Graph Name<span class="required">*</span></label>
                                                {!! Form::select('graph_id', [''=>'Select Graph'] + $graphs, null, ['class' => 'form-control']) !!}
                                            </div> 
                                        </div>
                                        <div class="clearfix">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label">Title<span class="required">*</span></label>
                                                {!! Form::text('en_title',null,['class' => 'form-control', 'data-required' => false,'placeholder'=>'Enter Graph Title']) !!}
                                            </div>
                                            <div class="col-md-6">
                                                    <label class="control-label">Graph Date:<span class="required">*</span></label>
                                                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                                                        {!! Form::text('date',null,['class' => 'form-control pick_date', 'data-required' => false,'placeholder' => 'Select Graph Date']) !!}
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>                  
                                        </div> 
                                        <div class="clearfix">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-md-12">  
                                                <label class="control-label">Description<span class="required">*</span></label>
                                                {!! Form::textarea('en_description',null,['class' => 'form-control ckeditor','data-required' => false,'rows'=>'5']) !!}
                                            </div>
                                        </div>
                                        <div class="clearfix">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="hidden" name="language" value="english">
                                                <input type="submit" value="Save" class="btn btn-success pull-right" />
                                            </div>
                                        </div>
                                    {!! Form::close() !!}
                                    </div> 
                                    </div>
                                    <div class="tab-pane" id="portlet_tab_2">

                                        <div class="form-body">
                                    {!! Form::model($formObj,['method' => $method,'files' => true, 'route' => [$action_url,$action_params],'class' => 'sky-form form form-group slider_form', 'id' => 'sp-frm']) !!} 
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label">Nombre del gráfico<span class="required">*</span></label>
                                                {!! Form::select('graph_id', [''=>'Seleccionar gráfico'] + $graphs, null, ['class' => 'form-control']) !!}
                                            </div> 
                                        </div>
                                        <div class="clearfix">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label">Título<span class="required">*</span></label>
                                                {!! Form::text('sn_title',null,['class' => 'form-control', 'data-required' => false,'placeholder'=>'Ingrese el título del gráfico']) !!}
                                            </div>
                                            <div class="col-md-6">
                                                    <label class="control-label">Fecha de gráfico:<span class="required">*</span></label>
                                                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                                                        {!! Form::text('date',null,['class' => 'form-control pick_date', 'data-required' => false,'placeholder' => 'Seleccionar fecha de gráfico']) !!}
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>                  
                                        </div> 
                                        <div class="clearfix">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-md-12">  
                                                <label class="control-label">Descripción<span class="required">*</span></label>
                                                {!! Form::textarea('sn_description',null,['class' => 'form-control ckeditor','data-required' => false,'rows'=>'5']) !!}
                                            </div>
                                        </div>
                                        <div class="clearfix">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="hidden" name="language" value="spanish">
                                                <input type="submit" value="Salvar" class="btn btn-success pull-right" />
                                            </div>
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
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    
<script type="text/javascript">
    $(document).ready(function () {
        $('#spanish').on('click',function(){
            $('#main_title').html('Agregar control deslizante de gráfico CMS');
        });
        $('#english').on('click',function(){
            $('#main_title').html('Add CMS Graph Slider');
        });

        $('.slider_form').submit(function () {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        
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


