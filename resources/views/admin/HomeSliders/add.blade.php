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
                    <!-- Country -->
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Country <span class="required">*</span></label>
                                    {!! Form::select('country_id',[''=>'Select Country']+ $countries,null,['class' => 'country form-control', 'data-required' => true]) !!}
                                </div>
                            </div>
                    <!-- Graph Type -->
                            <div class="clearfix">&nbsp;</div>
                            <div class="row" style="display: none;" id="graph_type_row">
                                <div class="col-md-12" >
                                    <label class="control-label">Graph Type<span class="required">*</span></label>
                                    {!! Form::select('graph_type',[''=>'Select Graph Type']+$graphTypes,null,['class' => 'form-control', 'data-required' => true,'id'=>'graph_type_id']) !!}
                                </div>
                            </div>
                        <div class="down_content" style="display: none;">
                        <!-- Security -->
                        <div class="row" id="security_id" style="display: none;">
                        <div class="clearfix">&nbsp;</div>
                            <div class="col-md-12">
                                <label class="control-label">Security<span class="required">*</span></label>
                                {!! Form::select('security_id',[''=>'Select Security']+$graphs,null,['class' => 'form-control graphs' , 'data-required' => false,'id'=>'security_id_val']) !!}
                            </div>
                        </div>
                        <!-- Second security -->
                        <div class="row" id="option_security_div" style="display: none;">
                            <div class="clearfix">&nbsp;</div>
                                <div class="col-md-12" >
                                <label class="control-label">Second Security<span class="required">*</span></label>
                                {!! Form::select('option_security',[''=>'Select Security']+$option_security,null,['class' => 'form-control' , 'data-required' => false,'id'=>'option_security_val']) !!}
                            </div>
                        </div>
                        <!--  Period  -->
                        <div id="period_div" style="display: none;">
                        <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-12" >
                                    <label class="control-label">Graph Period<span class="required">*</span></label>
                                {!! Form::select('option_period',[''=>'Select Period']+$months,$selected_month,['class' => 'form-control', 'data-required' => false,'id'=>'graph_period_id']) !!}
                            </div>
                                </div>
                            </div>
                        <!-- Price -->
                        <div id="prices_div" style="display: none;">
                            <div class="clearfix">&nbsp;</div>
                        <div class="row">
                                <div class="col-md-12">
                                <label class="control-label">Price<span class="required">*</span></label>
                                {!! Form::select('option_prices',[''=>'Select Option']+$prices,$selected_prices,['class' => 'form-control','id'=>'option_price_id']) !!}
                            </div>
                                </div>
                            </div>
                        <!-- Maturity -->
                        <div id="maturity_div" style="display: none;">
                        <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Maturity/Duration<span class="required">*</span></label>
                                    {!! Form::select('option_maturity',[''=>'Select Option']+$maturities,$selected_maturities,['class' => 'form-control','id'=>'option_maturity_id']) !!}
                                </div>
                            </div>
                                </div>
                        <!-- Markets -->
                        <div id="market_div" style="display: none;">
                        <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-12">
                                <label class="control-label">Markets<span class="required">*</span></label>
                                {!! Form::select('option_market',[''=>'Select Option']+$marketTypes,null,['class' => 'form-control','id'=>'option_market_id']) !!}
                                </div>
                            </div>
                                </div>
                        <!-- Rating -->
                        <div id="rating_div"  style="display: none;">
                        <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label">Rating / OECD <span class="required">*</span></label>
                                    {!! Form::select('option_rating',[''=>'Select Option']+$rating_orcd,null,['class' => 'form-control', 'data-required' => false, 'id'=>'option_maturity_id']) !!}
                                    </div>
                                </div>
                            </div>
                        <!-- Banchmark -->
                        <div id="banchmark_div"  style="display: none;">
                            <div class="clearfix">&nbsp;</div>
                            <div class="row">
                                <div class="col-md-12">
                                <label class="control-label">Banchmark</label>
                                {!! Form::select('option_banchmark',[''=>'Select Option'],null,['class' => 'form-control','id'=>'option_banchmark']) !!}
                            </div>
                                </div>
                            </div>
                        <!-- Credit Equities -->
                        <div id="credit_div"  style="display: none;">
                            <div class="clearfix">&nbsp;</div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label">Credit / Equities <span class="required">*</span></label>
                                    {!! Form::select('option_credit',[''=>'Select Option']+$credit_equities,null,['class' => 'form-control', 'data-required' => false, 'id'=>'option_maturity_id']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="display: none;">
                                <div class="col-md-12">
                                    <label class="control-label">Order<span class="required">*</span></label>
                            {!! Form::number('order',$orderMax,['class' => 'form-control', 'data-required' => false,'min'=>1]) !!}
                                </div>
                            </div>
                            <div class="row" style="display: none;">
                                <div class="col-md-12">
                                <div class="clearfix">&nbsp;</div>
                                    <label class="control-label">Status<span class="required">*</span></label>
                            {!! Form::select('status',[1=>'Active',0=>'Inactive'],null,['class' => 'form-control', 'data-required' => false]) !!}
                                </div>
                            </div>
                			<div>

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
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Display Date<span class="required">*</span></label>
                                        {!! Form::text('display_date',null, ['class' => 'form-control display_date', 'data-required' => false]) !!}
                                    </div>
                                </div>
                            </div>
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
                                    {!! Form::textarea('post_description['.$lng.'][]',$description,['class' => 'form-control ckeditor', 'id' => 'ckeditor'.$lng]) !!}
                                </div>
                            </div>
                            @endforeach
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
