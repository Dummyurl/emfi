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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $data['benchmark_family_list'] = Securities::where('benchmark_family', "!=", "")
                                                   ->groupBy('benchmark_family')
                                                   ->pluck("benchmark_family","benchmark_family")->all();

        return view($this->moduleViewName.'.edit', $data);
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
        $model = Securities::find($id);
        // check validations
        if(!$model)
        {
            $status = 0;
            $msg = "Record not found !";
            return ['status' => $status,'msg' => $msg, 'data' => $data]; 
        }

        $validator = Validator::make($request->all(), [
            'country_id' => 'required|exists:'.TBL_COUNTRY.',id',
            'market_id' => 'required|exists:'.TBL_MARKETS.',id',
            'CUSIP' => 'required|min:2',
            'ticker' => 'required',
            'cpn' => 'required',
            'security_name' => 'required',       
            'benchmark' => Rule::in([1,0]),
        ]);

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
            $benchmark = $request->get('benchmark');
            $new_benchmark = $request->get('new_benchmark_family');
            $select_benchmark = $request->get('benchmark_family');
            $benchmark_family = null;

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
           
            $input = $request->all();
            $model->update($input);
            
            $market_id = $request->get('market_id');
            if($market_id != 5)
            {
                $model->maturity_date = null;
            }
            $model->benchmark_family = $benchmark_family;
            $model->benchmark = $benchmark;
            
            $country_id = $request->get('country_id');
            $country = \App\Models\Country::find($country_id);
            
            if($country){
                $model->country =$country->country_code;
                $model->save();
            }           

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
