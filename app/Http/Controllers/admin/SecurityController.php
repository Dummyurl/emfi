<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Models\MarketType;
use App\Models\Securities;
use App\Models\AdminAction;
use App\Models\Country;
use Excel;
use Validator;
use Datatables;

class SecurityController extends Controller
{
    public function __construct() {

        $this->moduleRouteText = "securities";
        $this->moduleViewName = "admin.securities";
        $this->list_url = route($this->moduleRouteText.".index");

        $module = "Security";
        $this->module = $module;  

        $this->adminAction= new AdminAction; 
        
        $this->modelObj = new Securities();  

        $this->addMsg = $module . " has been added successfully!";
        $this->updateMsg = $module . " has been updated successfully!";
        $this->deleteMsg = $module . " has been deleted successfully!";
        $this->deleteErrorMsg = $module . " can not deleted!";       

        view()->share("list_url", $this->list_url);
        view()->share("moduleRouteText", $this->moduleRouteText);
        view()->share("moduleViewName", $this->moduleViewName);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$LIST_SECURITY);
        
        if($checkrights) 
        {
            return $checkrights;
        }
         
        $data = array();        
        $data['page_title'] = "Manage Securities";
        $data['MarketType'] = MarketType::getArrayList();  
        $data['Countries'] = Country::getCountryList();
        $data['btnAdd'] = \App\Models\Admin::isAccess(\App\Models\Admin::$ADD_SECURITY);

        if($request->get("changeDefault") > 0)
        {
            $security_id = $request->get("changeID");   
            $status = $request->get("changeDefault");

            $rows = \App\Models\Securities::find($security_id);
            
                if($rows)
                {
                    $market_id = $rows->market_id;
                    if($market_id)
                    {
                        $marketRows = Securities::where('market_id',$market_id)->get();
                        if($marketRows)
                        {
                            foreach ($marketRows as $mk)
                            {
                                $mk->default = 0;
                                $mk->save();
                            }
                        }
                    }
                    $status = $rows->status;

                    if($status == 0)
                        $status = 1;
                    else
                        $status = 0;

                    $rows->default = $status;
                    $rows->save();

                    //store logs detail
                    $params=array();
                    $adminAction = new AdminAction();
                    
                    $params['adminuserid']  = \Auth::guard('admins')->id();
                    $params['actionid']     = $adminAction->EDIT_SECURITY;
                    $params['actionvalue']  = $security_id;
                    $params['remark']       = "Change Default Sataus::".$security_id;

                    $logs=\App\Models\AdminLog::writeadminlog($params);      

                    session()->flash('success_message', "Status has been changed successfully.");
                    return redirect($this->list_url);
                }
                else
                {
                    session()->flash('success_message', "Status not changed, Please try again");
                    return redirect($this->list_url);
                }

            return redirect($this->list_url);
        }

        if($request->get("changeAnalyzerDefault") > 0)
        {
            $security_id = $request->get("changeID");
            $status = $request->get("changeAnalyzerDefault");
            $rows = \App\Models\Securities::find($security_id);
            if($rows)
            {
                $market_id = $rows->market_id;
                $count_Rows = Securities::where('market_id',$market_id)->where('bond_default', 1)->orderBy('updated_at')->count();

                if($count_Rows >=2){
                    $marketRows = Securities::where('market_id',$market_id)->where('bond_default', 1)->orderBy('updated_at')->first();
                    if($marketRows)
                    {
                        $marketRows->bond_default = 0;
                        $marketRows->save();
                    }
                }

                $rows->bond_default = $status;
                $rows->save();

                //store logs detail
                $params=array();
                $adminAction = new AdminAction();
                
                $params['adminuserid']  = \Auth::guard('admins')->id();
                $params['actionid']     = $adminAction->EDIT_SECURITY;
                $params['actionvalue']  = $security_id;
                $params['remark']       = "Change Default Sataus::".$security_id;

                $logs=\App\Models\AdminLog::writeadminlog($params);      

                session()->flash('success_message', "Status has been changed successfully.");
                return redirect($this->list_url);
            } else {
                session()->flash('success_message', "Status not changed, Please try again");
                return redirect($this->list_url);
            }

            return redirect($this->list_url);
        }

        return view($this->moduleViewName.".index", $data);    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$ADD_SECURITY);
        
        if($checkrights) 
        {
            return $checkrights;
        }
        
        $data = array();
        $data['formObj'] = $this->modelObj;
        $data['page_title'] = "Add ".$this->module;
        $data['action_url'] = $this->moduleRouteText.".store";
        $data['action_params'] = 0;
        $data['buttonText'] = "Save";
        $data["method"] = "POST"; 
        $data['countries'] = Country::getCountryList();
        $data['markets'] = \App\Models\MarketType::pluck('market_name','id')->all();
        $data['tickers'] = \App\Models\Tickers::pluck('ticker_name','id')->all();
        $data['ratings'] = \DB::table('sp_rating')->pluck('sp_name','id')->all();
        $data['oecds'] = \App\Models\Securities::getCurrentOECD();
        $data['benchmark_family_list'] = Securities::where('benchmark_family', "!=", "")
        ->groupBy('benchmark_family')->pluck("benchmark_family","benchmark_family")->all();

        return view($this->moduleViewName.'.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$ADD_SECURITY);
        
        if($checkrights) 
        {
            return $checkrights;
        }

        $status = 1;
        $msg = $this->addMsg;
        $data = array();
        $oecds = array_keys(\App\Models\Securities::getCurrentOECD());
        $rules = [
                'CUSIP.required'=>'CUSIP is required !',
                'CUSIP.unique'=>'CUSIP is already exists !',
                ];
        $input = array_map('trim', $request->all());
        $validator = Validator::make($input, [
            'country_id' => 'required|exists:'.TBL_COUNTRY.',id',
            'market_id' => 'required|exists:'.TBL_MARKETS.',id',
            'CUSIP' => 'required|min:2|unique:'.TBL_SECURITY.',CUSIP',
            'ticker_id' => 'required|exists:tickers,id',
            'security_name' => 'required',
            'display_order' => 'numeric|min:0',
            'benchmark' => Rule::in([1,0]),
        ],$rules);
        
        // check validations
        if ($validator->fails())
        {
            $messages = $validator->messages();
            
            $status = 0;
            $msg = "";
            
            foreach ($messages->all() as $message) 
            {
                $msg .= $message . "<br />";
            }
        }
        else
        {
            $security = new Securities();

            $market_id          = $request->get('market_id');
            $country_id         = $request->get('country_id');
            $ticker_id          = $request->get('ticker_id');
            $sp_rating_id       = $request->get('sp_rating_id');
            $benchmark          = $request->get('benchmark');
            $new_benchmark      = $request->get('new_benchmark_family');
            $select_benchmark   = $request->get('benchmark_family');
            $maturity_date      = $request->get('maturity_date');
            $display_title      = $request->get('display_title');
            $cpn                = $request->get('cpn');
            $display_order      = $request->get('display_order');
            $current_oecd_member_cor_class = $request->get('current_oecd_member_cor_class');
            $benchmark_family = null;
            if($market_id == 5)
            {
                if(empty($sp_rating_id))
                {
                    $status = 0;
                    $msg = 'S&P rating is required !';
                    return ['status' => $status,'msg' => $msg, 'data' => $data];
                }
                if(empty($current_oecd_member_cor_class))
                {
                    $status = 0;
                    $msg = 'OECD is required !';
                    return ['status' => $status,'msg' => $msg, 'data' => $data];
                }
                if(empty($cpn))
                {
                    $status = 0;
                    $msg = 'CPN is required !';
                    return ['status' => $status,'msg' => $msg, 'data' => $data];
                }
                if(empty($display_title))
                {
                    $status = 0;
                    $msg = 'Display title is required !';
                    return ['status' => $status,'msg' => $msg, 'data' => $data];
                }
                if(empty($maturity_date))
                {
                    $status = 0;
                    $msg = 'Maturity date is required !';
                    return ['status' => $status,'msg' => $msg, 'data' => $data];
                }
            } elseif ($market_id == 1)
            {
                if(empty($sp_rating_id))
                {
                    $status = 0;
                    $msg = 'S&P rating is required !';
                    return ['status' => $status,'msg' => $msg, 'data' => $data];
                }
                if(empty($current_oecd_member_cor_class))
                {
                    $status = 0;
                    $msg = 'OECD is required !';
                    return ['status' => $status,'msg' => $msg, 'data' => $data];
                }
            } else {
                $maturity_date = null;
                $display_title = null;
                $sp_rating_id = null;
                $current_oecd_member_cor_class = null;
                $cpn = null;
            }

            if($benchmark == 1)
            {
                if(empty($new_benchmark) && empty($select_benchmark)){
                    $status = 0;
                    $msg = 'please enter at least one benchmark!';
                    return ['status' => $status, 'msg'=>$msg];
                }
                if (!empty($new_benchmark) && !empty($select_benchmark)) {
                    $status = 0;
                    $msg = 'Please enter only one benchmark';
                    return ['status' => $status, 'msg'=>$msg];
                }

                if (isset($new_benchmark) && !empty($new_benchmark)) {
                    $benchmark_family = $new_benchmark;
                }
                elseif (isset($select_benchmark) && !empty($select_benchmark)) {
                    $benchmark_family = $select_benchmark;
                }
            }

            

            $security->CUSIP = $request->get('CUSIP');
            $security->market_id = $market_id;
            $security->country_id = $country_id;
            $security->ticker_id = $ticker_id;
            $security->sp_rating_id = $sp_rating_id;

            $security->current_oecd_member_cor_class = $current_oecd_member_cor_class;
            $security->cpn = $cpn;
            $security->security_name = $request->get('security_name');
            $security->display_title = $display_title;
            $security->maturity_date = $maturity_date;
            $security->benchmark_family = $benchmark_family;
            $security->benchmark = $benchmark;
            $security->display_order = $display_order;
     
            $security->save();
            $id = $security->id;
            //store logs detail
            $params=array();
            $adminAction = new AdminAction();
            
            $params['adminuserid']  = \Auth::guard('admins')->id();
            $params['actionid']     = $adminAction->ADD_SECURITY;
            $params['actionvalue']  = $id;
            $params['remark']       = "Add Security::".$id;

            $logs=\App\Models\AdminLog::writeadminlog($params);

            session()->flash('success_message', $msg);

        }
        return ['status' => $status,'msg' => $msg, 'data' => $data]; 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$EDIT_SECURITY);
        
        if($checkrights) 
        {
            return $checkrights;
        }

        $formObj = $this->modelObj->find($id);

        if(!$formObj)
        {
            abort(404);
        }   

        $data = array();
        $data['formObj'] = $formObj;
        $data['page_title'] = "Edit ".$this->module;
        $data['buttonText'] = "Update";
        $data['action_url'] = $this->moduleRouteText.".update";
        $data['action_params'] = $formObj->id;
        $data['method'] = "PUT";
        $data['countries'] = Country::getCountryList();
        $data['markets'] = \App\Models\MarketType::pluck('market_name','id')->all();
        $data['tickers'] = \App\Models\Tickers::pluck('ticker_name','id')->all();
        $data['ratings'] = \DB::table('sp_rating')->pluck('sp_name','id')->all();
        $data['oecds'] = \App\Models\Securities::getCurrentOECD();
        $data['benchmark_family_list'] = Securities::where('benchmark_family', "!=", "")
                                                   ->groupBy('benchmark_family')
                                                   ->pluck("benchmark_family","benchmark_family")->all();

        return view($this->moduleViewName.'.add', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$EDIT_SECURITY);

        if($checkrights)
        {
            return $checkrights;
        }
        $status = 1;
        $msg = 'Security has been updated successfully !';
        $data = array();        
        $oecds = array_keys(\App\Models\Securities::getCurrentOECD());
        $model = Securities::find($id);
        // check validations
        if(!$model)
        {
            $status = 0;
            $msg = "Record not found !";
            return ['status' => $status,'msg' => $msg, 'data' => $data]; 
        }

        $rules = [
                'CUSIP.required'=>'CUSIP is required !',
                'CUSIP.unique'=>'CUSIP is already exists !',
                ];
        $input = array_map('trim', $request->all());
        $validator = Validator::make($input, [
            'country_id' => 'required|exists:'.TBL_COUNTRY.',id',
            'market_id' => 'required|exists:'.TBL_MARKETS.',id',
            'CUSIP' => 'required|min:2|unique:'.TBL_SECURITY.',CUSIP,'.$id,
            'ticker_id' => 'required|exists:tickers,id',
            'security_name' => 'required',
            'display_order' => 'numeric|min:0',
            'benchmark' => Rule::in([1,0]),
        ],$rules);

        if ($validator->fails()) 
        {
            $messages = $validator->messages();
            
            $status = 0;
            $msg = "";
            
            foreach ($messages->all() as $message) 
            {
                $msg .= $message . "<br />";
            }
        }
        else
        {
            $market_id = $request->get('market_id');
            $country_id = $request->get('country_id');
            $ticker_id = $request->get('ticker_id');
            $sp_rating_id = $request->get('sp_rating_id');
            $benchmark = $request->get('benchmark');
            $new_benchmark = $request->get('new_benchmark_family');
            $select_benchmark = $request->get('benchmark_family');
            $maturity_date = $request->get('maturity_date');
            $display_title = $request->get('display_title');
            $display_order = $request->get('display_order');
            $current_oecd_member_cor_class = $request->get('current_oecd_member_cor_class');
            $cpn = $request->get('cpn');
            $benchmark_family = null;
            
             if($market_id == 5)
            {
                if(empty($sp_rating_id))
                {
                    $status = 0;
                    $msg = 'S&P rating is required !';
                    return ['status' => $status,'msg' => $msg, 'data' => $data];
                }
                if(empty($current_oecd_member_cor_class))
                {
                    $status = 0;
                    $msg = 'OECD is required !';
                    return ['status' => $status,'msg' => $msg, 'data' => $data];
                }
                if(empty($cpn))
                {
                    $status = 0;
                    $msg = 'CPN is required !';
                    return ['status' => $status,'msg' => $msg, 'data' => $data];
                }
                if(empty($display_title))
                {
                    $status = 0;
                    $msg = 'Display title is required !';
                    return ['status' => $status,'msg' => $msg, 'data' => $data];
                }
                if(empty($maturity_date))
                {
                    $status = 0;
                    $msg = 'Maturity date is required !';
                    return ['status' => $status,'msg' => $msg, 'data' => $data];
                }
            } elseif ($market_id == 1)
            {
                if(empty($sp_rating_id))
                {
                    $status = 0;
                    $msg = 'S&P rating is required !';
                    return ['status' => $status,'msg' => $msg, 'data' => $data];
                }
                if(empty($current_oecd_member_cor_class))
                {
                    $status = 0;
                    $msg = 'OECD is required !';
                    return ['status' => $status,'msg' => $msg, 'data' => $data];
                }
            } else {
                $maturity_date = null;
                $display_title = null;
                $sp_rating_id = null;
                $current_oecd_member_cor_class = null;
                $cpn = null;
            }

            if($benchmark == 1)
            {
            
                if(empty($new_benchmark) && empty($select_benchmark)){
                    $status = 0;
                    $msg = 'please enter at least one benchmark!';
                    return ['status' => $status, 'msg'=>$msg];
                }
                if (!empty($new_benchmark) && !empty($select_benchmark)) {
                    $status = 0;
                    $msg = 'Please enter only one benchmark';
                    return ['status' => $status, 'msg'=>$msg];
                }
                if (isset($new_benchmark) && !empty($new_benchmark)) {
                    $benchmark_family = $new_benchmark;
                }
                elseif (isset($select_benchmark) && !empty($select_benchmark)) {
                    $benchmark_family = $select_benchmark;
                }
            }

            $model->CUSIP = $request->get('CUSIP');
            $model->market_id = $market_id;
            $model->country_id = $country_id;
            $model->ticker_id = $ticker_id;
            $model->sp_rating_id = $sp_rating_id;
            $model->current_oecd_member_cor_class = $current_oecd_member_cor_class;
            $model->cpn = $cpn;
            $model->security_name = $request->get('security_name');
            $model->display_title = $display_title;
            $model->maturity_date = $maturity_date;
            $model->benchmark_family = $benchmark_family;
            $model->benchmark = $benchmark;
            $model->display_order = $display_order;
            
                $model->save();

            //store logs detail
                $params=array();
                $adminAction = new AdminAction();
                
                $params['adminuserid']  = \Auth::guard('admins')->id();
                $params['actionid']     = $adminAction->EDIT_SECURITY;
                $params['actionvalue']  = $id;
                $params['remark']       = "Edit Security::".$id;

                $logs=\App\Models\AdminLog::writeadminlog($params);

            session()->flash('success_message', $msg);
        }
        
        return ['status' => $status,'msg' => $msg, 'data' => $data]; 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function data(Request $request)
    {
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$LIST_SECURITY);
        
        if($checkrights) 
        {
            return $checkrights;
        }
     
        $model = Securities::select(TBL_SECURITY.'.*',TBL_MARKETS.'.market_name',\DB::raw("CONCAT(".TBL_COUNTRY.".title,' (',".TBL_COUNTRY.".country_code,')')  AS country"))
                    ->join(TBL_MARKETS, TBL_SECURITY.'.market_id','=',TBL_MARKETS.'.id')
                ->leftJoin(TBL_COUNTRY,TBL_COUNTRY.".id","=",TBL_SECURITY.".country_id");

        return Datatables::eloquent($model)
            
            ->addColumn('action', function(Securities $row) {
                return view("admin.partials.action",
                    [
                    'currentRoute' => $this->moduleRouteText,
                    'row' => $row,                             
                    'isEdit' => \App\Models\Admin::isAccess(\App\Models\Admin::$EDIT_SECURITY),
                    'isDefault' => \App\Models\Admin::isAccess(\App\Models\Admin::$EDIT_SECURITY),                                                     
                    'isAnalyzerDefault' => \App\Models\Admin::isAccess(\App\Models\Admin::$EDIT_SECURITY),
                    ])->render();
            })
            
            ->editColumn('created_at', function($row){
                
                if(!empty($row->created_at))
                    return date("j M, Y h:i:s A",strtotime($row->created_at));
                else
                    return '-';    
            })
            ->addColumn('default',  function ($row){
                if($row->default == 1)
                    return '<a class="btn btn-success btn-xs">Yes</a>';
                else
                    return '';
            })
            ->addColumn('bond_default',  function ($row){
                if($row->bond_default == 1)
                    return '<a class="btn btn-info btn-xs">Yes</a>';
                else
                    return '';
            })
            ->rawColumns(['action','default', 'bond_default'])
            ->filter( function ($query){
                $search_cusip = request()->get('search_cusip');
                $search_market = request()->get('search_market');
                $search_status = request()->get('search_status');
                $search_analyzer_default = request()->get('search_analyzer_default');
                $search_country = request()->get("search_country"); 

                if (!empty($search_cusip)) {
                    $query = $query->where(TBL_SECURITY.'.CUSIP',"LIKE", '%'.$search_cusip.'%');
                }
                if(!empty($search_country))
                    {
                        $query = $query->where(TBL_COUNTRY.".id", '=', $search_country);
                    }
                if (!empty($search_market)) {
                    $query = $query->where(TBL_SECURITY.'.market_id', '=', $search_market);
                }
                if($search_status == "1" || $search_status == "0")
                {
                    $query = $query->where(TBL_SECURITY.".default", $search_status);
                }
                if($search_analyzer_default == "1" || $search_analyzer_default == "0")
                {
                    $query = $query->where(TBL_SECURITY.".bond_default", $search_analyzer_default);
                }
            })
            ->make(true);       
    }
}
