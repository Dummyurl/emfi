<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-search"></i>Advance Search
        </div>
        <div class="tools">
            <a href="javascript:;" class="expand"> </a>
        </div>
    </div>
    <div class="portlet-body" style="display: none">
        <form id="search-frm">
            <div class="row">
                <div class="col-md-4">
                    <label class="control-label">CUSIP Name</label>
                    <input type="text" value="{{ \Request::get("search_cusip") }}" class="form-control" name="search_cusip" />
                </div>
                <div class="col-md-4">
                    <label class="control-label">Market Type</label>
                     {!! Form::select('search_market', [''=>'Select Market Type'] + $MarketType, null,   ['class' => 'form-control market_type']) !!}
                </div>
                <div class="col-md-4">
                    <label class="control-label">Economic Default</label>
                    <select name="search_status" class="form-control">
                        <option value="all">All</option>                        
                        <option value="1" {!! \Request::get("search_status") == 1 ? 'selected="selected"':'' !!}>Yes</option>                        
                        <option value="0" {!! \Request::get("search_status") == "0" ? 'selected="selected"':'' !!}>No</option>                        
                    </select>
                </div>
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="row">
                <div class="col-md-4">
                    <label class="control-label">Analyzer Default</label>
                    <select name="search_analyzer_default" class="form-control">
                        <option value="all">All</option>                        
                        <option value="1" {!! \Request::get("search_analyzer_default") == 1 ? 'selected="selected"':'' !!}>Yes</option>                        
                        <option value="0" {!! \Request::get("search_analyzer_default") == "0" ? 'selected="selected"':'' !!}>No</option>                        
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="control-label">Country</label>
                     {!! Form::select('search_country', [''=>'Select Country'] + $Countries, null, ['class' => 'form-control']) !!}

                </div>
                <div class="col-md-4">
                    <input type="submit" class="btn blue mTop25" value="Search"/>
                    &nbsp;
                    <a href="" class="btn red mTop25">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>
