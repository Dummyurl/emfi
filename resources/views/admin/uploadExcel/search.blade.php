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
                    <label class="control-label">Company Name</label>
                    <input type="text" value="{{ \Request::get("search_id") }}" class="form-control" name="search_cusip" />
                </div>
                <div class="col-md-4">
                    <label class="control-label">Market Type</label>
                     {!! Form::select('search_market', [''=>'Select Market Type'], null,   ['class' => 'form-control market_type']) !!}
                </div>

            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="row">
                <div class="col-md-4 pull-right">
                    <input type="submit" class="btn blue mTop25" value="Search"/>
                    &nbsp;
                    <a href="" class="btn red mTop25">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>
