<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-file"></i>Add News
        </div>
        
    </div>
    <div class="portlet-body">
        <div class="portlet-body">
             <div class="form-body">
                            {!! Form::model($formObj,['method' => $method,'files' => true, 'route' => [$action_url,$action_params],'class' => 'sky-form form form-group', 'id' => 'main-frm']) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Country <span class="required">*</span></label>
                                    {!! Form::select('country_id',[''=>'Select Country']+ $countries,null,['class' => 'country form-control', 'data-required' => true]) !!}
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row" style="display: none;" id="graph_type_row">
                                <div class="col-md-12" >
                                    <label class="control-label">Graph Type<span class="required">*</span></label>
                                    {!! Form::select('graph_type',[''=>'Select Graph Type']+$graphTypes,null,['class' => 'form-control', 'data-required' => true,'id'=>'graph_type_id']) !!}
                                </div>
                            </div>
                        <div class="down_content" style="display: none;">
                            <div class="row">
                            <div class="clearfix">&nbsp;</div>
                                <div class="col-md-12" >
                                    <label class="control-label">Graph Period<span class="required">*</span></label>
                                    {!! Form::select('graph_period',[''=>'Select Period']+$months,$selected_month,['class' => 'form-control', 'data-required' => true,'id'=>'graph_period_id']) !!}
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row" id="security_id" style="display: none;">
                                <div class="col-md-12">
                                    <label class="control-label">Security<span class="required">*</span></label>
                                    {!! Form::select('security_id',[''=>'Select Security']+$graphs,null,['class' => 'form-control graphs' , 'data-required' => false,'id'=>'security_id_val']) !!}
                                </div>
                            </div>
                            <div id="yield_curve_div" style="display: none;">
                                <div id="option_maturity_div" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label">Maturity/Duration<span class="required">*</span></label>
                                            {!! Form::select('option_maturity',[''=>'Select Option']+$maturities,$selected_maturities,['class' => 'form-control','id'=>'option_maturity_id']) !!}
                                        </div>
                                    </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Maturity/Duration<span class="required">*</span></label>
                                    {!! Form::select('option_maturity',[''=>'Select Option']+$maturities,$selected_maturities,['class' => 'form-control','id'=>'option_maturity_id']) !!}
                                </div>
                            </div>
                                </div>
                            <div id="relval_option_2"  style="display: none;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label">Rating / OECD <span class="required">*</span></label>
                                        {!! Form::select('option_rating',[''=>'Select Option']+$rating_orcd,null,['class' => 'form-control', 'data-required' => true, 'id'=>'option_maturity_id']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Price<span class="required">*</span></label>
                                    {!! Form::select('option_price',[''=>'Select Option']+$prices,$selected_prices,['class' => 'form-control','id'=>'option_price_id']) !!}
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div id="relval_option_4"  style="display: none;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label">Credit / Equities <span class="required">*</span></label>
                                        {!! Form::select('option_credit',[''=>'Select Option']+$credit_equities,null,['class' => 'form-control', 'data-required' => true, 'id'=>'option_maturity_id']) !!}
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="row" style="display: none;">
                                <div class="col-md-12">
                                    <label class="control-label">Order<span class="required">*</span></label>
                                    {!! Form::number('order',$orderMax,['class' => 'form-control', 'data-required' => true,'min'=>1]) !!}
                                </div>
                            </div>
                            <div class="row" style="display: none;">
                                <div class="col-md-12">
                                <div class="clearfix">&nbsp;</div>
                                    <label class="control-label">Status<span class="required">*</span></label>
                                    {!! Form::select('status',[1=>'Active',0=>'Inactive'],null,['class' => 'form-control', 'data-required' => true]) !!}
                                </div>
                            </div>
                            
                            @foreach($languages as $lng => $val)
                            <?php 
                                $title = null;
                                $description = null;
                                if(isset($formObj->id) && !empty($formObj->id)){
                                $trans =  \App\Models\HomeSliderTranslation::where('locale',$lng)->where('home_slider_id',$formObj->id)->first();
                                if($trans)
                                {
                                    $title = $trans->title;
                                    $description = $trans->description;
                                }
                            }?>
                            <div class="clearfix">&nbsp;</div>
                            <div class="note note-info">
                                <div class="row">
                                    <div class="col-md-10" style="padding-left: 30px; height: 14px;">
                                        <h4>For {{ $val }}</h4>
                                    </div>   
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="" class="control-label">Post Title [{{ $lng }}]
                                    </label>
                                    {!! Form::text('post_title['.$lng.'][]',$title,['class' => 'form-control setValue']) !!}
                                </div>
                                <div class="clearfix">&nbsp;</div>
                                <div class="col-md-12">
                                    <label for="" class="control-label">Post Description [{{ $lng }}]
                                    </label>
                                    {!! Form::textarea('post_description['.$lng.'][]',$description,['class' => 'form-control ckeditor']) !!}
                                </div>
                            </div>
                            @endforeach
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