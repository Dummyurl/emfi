<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Validator;
use Datatables;
use App\Models\HomeSlider;
use App\models\AdminLog;
use App\Models\AdminAction;

class HomeSlidersController extends Controller
{
    public function __construct() {

        $this->moduleRouteText = "home-sliders";
        $this->moduleViewName = "admin.HomeSliders";
        $this->list_url = route($this->moduleRouteText.".index");

        $module = "Home Slider";
        $this->module = $module;  

        $this->adminAction= new AdminAction; 
        
        $this->modelObj = new HomeSlider();  

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
    public function index()
    {
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$LIST_HOME_SLIDER);
        
        if($checkrights) 
        {
            return $checkrights;
        }
         
        $data = array();        
        $data['page_title'] = "Manage Home Sliders";
        $data['add_url'] = route($this->moduleRouteText.'.create');
        $data['btnAdd'] = \App\Models\Admin::isAccess(\App\Models\Admin::$ADD_HOME_SLIDER);
        
        return view($this->moduleViewName.".index", $data);    
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$ADD_HOME_SLIDER);
        
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
        $data['posts'] = \App\Models\Post::pluck('title','id')->all();
        $data['graphs'] = \App\Models\Securities::pluck('CUSIP','id')->all();
        $data['countries'] = \App\Models\Country::pluck('title','id')->all();

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

        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$ADD_HOME_SLIDER);
        
        if($checkrights) 
        {
            return $checkrights;
        }

        $status = 1;
        $msg = $this->addMsg;
        $data = array();

        $validator = Validator::make($request->all(), [
            'slider_type' => ['required', Rule::in(['graph','post'])],
            'post_id' => 'exists:'.TBL_POST.',id',
            'security_id' => 'exists:securities,id',
            //'country_id' => 'required|exists:'.TBL_COUNTRY.',id',
            'graph_type' => ['required', Rule::in(['line'])],
            'status' => ['required', Rule::in([1,0])],
            'order' => 'required|min:0',
        ]);
        
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
            $slider_type = $request->get('slider_type');
            $post_id = $request->get('post_id');
            $security_id = $request->get('security_id');
            $country_id = $request->get('country_id');
            $graph_type = $request->get('graph_type');
            $statuss = $request->get('status');
            $order = $request->get('order');

            if($slider_type == 'graph' && !empty($post_id)){
                $status = 0;
                $msg = 'Please enter valid graph name';
                return ['status' => $status, 'msg' => $msg];
            }
            if($slider_type == 'post' && !empty($security_id)){
                $status = 0;
                $msg = 'Please enter valid post name';
                return ['status' => $status, 'msg' => $msg];
            }
            $obj = new HomeSlider();
            $obj->slider_type = $slider_type;
            if(!empty($post_id))
                $obj->post_id = $post_id;
            if(!empty($security_id))
            $obj->security_id = $security_id;
            
			if($country_id > 0)
			$obj->country_id = $country_id;
			
            $obj->graph_type = $graph_type;
            $obj->status = $statuss;
            $obj->order = $order;
            $obj->save();

            $id = $obj->id;
            //store logs detail
            $params=array();    
                                    
            $params['adminuserid']  = \Auth::guard('admins')->id();
            $params['actionid']     = $this->adminAction->ADD_HOME_SLIDER;
            $params['actionvalue']  = $id;
            $params['remark']       = "Add Home Slider::".$id;
                                    
            $logs=\App\Models\AdminLog::writeadminlog($params);

            session()->flash('success_message', $msg);           
            
        }
        
        return ['status' => $status, 'msg' => $msg, 'data' => $data];
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
        
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$EDIT_HOME_SLIDER);
        
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
        $data['posts'] = \App\Models\Post::pluck('title','id')->all();
        $data['graphs'] = \App\Models\Securities::pluck('CUSIP','id')->all();
        $data['countries'] = \App\Models\Country::pluck('title','id')->all();

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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$EDIT_HOME_SLIDER);
        
        if($checkrights) 
        {
            return $checkrights;
        }

        $model = $this->modelObj->find($id);

        $status = 1;
        $msg = $this->updateMsg;
        $data = array();        
        
        $validator = Validator::make($request->all(), [
            'slider_type' => [Rule::in(['graph','post'])],
            'post_id' => 'exists:'.TBL_POST.',id',
            'security_id' => 'exists:securities,id',
            'country_id' => 'required|exists:'.TBL_COUNTRY.',id',
            'graph_type' => ['required', Rule::in(['line'])],
            'status' => ['required', Rule::in([1,0])],
            'order' => 'required|min:0|numeric',
        ]);
        
        // check validations
        if(!$model)
        {
            $status = 0;
            $msg = "Record not found !";
        }
        else if ($validator->fails()) 
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
            $post_id = $request->get('post_id');
            $security_id = $request->get('security_id');
            $country_id = $request->get('country_id');
            $graph_type = $request->get('graph_type');
            $statuss = $request->get('status');
            $order = $request->get('order');

            if(!empty($security_id) && !empty($post_id)){
                $status = 0;
                $msg = 'Please enter only one slider type';
                return ['status' => $status, 'msg' => $msg];
            }

            $model->security_id = $security_id;
            $model->post_id = $post_id;
            $model->country_id = $country_id;
            $model->graph_type = $graph_type;
            $model->status = $statuss;
            $model->order = $order;
            $model->save();

            //store logs detail
                $params=array();
                
                $params['adminuserid']  = \Auth::guard('admins')->id();
                $params['actionid']     = $this->adminAction->EDIT_HOME_SLIDER;
                $params['actionvalue']  = $id;
                $params['remark']       = "Edit Home Slider::".$id;

                $logs=\App\Models\AdminLog::writeadminlog($params);           
        }
        
        return ['status' => $status,'msg' => $msg, 'data' => $data]; 

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Request $request)
    {
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$DELETE_HOME_SLIDER);
        
        if($checkrights) 
        {
            return $checkrights;
        }

        $modelObj = $this->modelObj->find($id); 

        if($modelObj) 
        {
            try 
            {             
                $backUrl = $request->server('HTTP_REFERER');
                $modelObj->delete();
                session()->flash('success_message', $this->deleteMsg); 

                //store logs detail
                $params=array();
                
                $params['adminuserid']  = \Auth::guard('admins')->id();
                $params['actionid']     = $this->adminAction->DELETE_HOME_SLIDER;
                $params['actionvalue']  = $id;
                $params['remark']       = "Delete Home Slider::".$id;

                $logs=\App\Models\AdminLog::writeadminlog($params);     

                return redirect($backUrl);
            } 
            catch (Exception $e) 
            {
                session()->flash('error_message', $this->deleteErrorMsg);
                return redirect($this->list_url);
            }
        } 
        else 
        {
            session()->flash('error_message', "Record not exists");
            return redirect($this->list_url);
        }
    }

    public function data(Request $request)
    {
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$LIST_HOME_SLIDER);
        
        if($checkrights) 
        {
            return $checkrights;
        }
     
        $model = HomeSlider::select(TBL_HOME_SLIDER.".*",TBL_POST.".title as post",TBL_COUNTRY.".title as country",TBL_POST.".title as post",TBL_SECURITY.".CUSIP as graph")
                ->leftJoin(TBL_SECURITY,TBL_SECURITY.".id","=",TBL_HOME_SLIDER.".security_id")
                ->leftJoin(TBL_POST,TBL_POST.".id","=",TBL_HOME_SLIDER.".post_id")
                ->leftJoin(TBL_COUNTRY,TBL_COUNTRY.".id","=",TBL_HOME_SLIDER.".country_id");
                     
        return Datatables::eloquent($model)
            
            ->addColumn('action', function(HomeSlider $row) {
                return view("admin.partials.action",
                    [
                        'currentRoute' => $this->moduleRouteText,
                        'row' => $row,                             
                        'isEdit' => \App\Models\Admin::isAccess(\App\Models\Admin::$EDIT_HOME_SLIDER),
                        'isDelete' => \App\Models\Admin::isAccess(\App\Models\Admin::$DELETE_HOME_SLIDER),
                                                     
                    ]
                    )->render();
            })
            
            ->editColumn('created_at', function($row){
                
                if(!empty($row->created_at))                    
                    
            return date("j M, Y h:i:s A",strtotime($row->created_at));
                else
                    return '-';    
            })
            ->editColumn('status', function($row){
                
                if($row->status == 1)        
                    return '<a class="btn btn-primary btn-xs">Active</a>';
                else
                    return '<a class="btn btn-danger btn-xs">Inactive</a>';
            })
            ->rawColumns(['action','status'])                
            ->filter(function ($query) 
                {
                    $search_start_date = trim(request()->get("search_start_date"));
                    $search_end_date = trim(request()->get("search_end_date"));
                    $search_post = request()->get("search_post");                    
                    $search_graph = request()->get("search_graph"); 
                    $search_country = request()->get("search_country"); 
                    $search_status = request()->get("search_status"); 

                    if (!empty($search_start_date)){

                    $from_date=$search_start_date.' 00:00:00';
                    $convertFromDate= $from_date;

                    $query = $query->where(TBL_HOME_SLIDER.".created_at",">=",addslashes($convertFromDate));
                    }
					
					if(!empty($search_country))
                    {
                        $query = $query->where(TBL_COUNTRY.".title", 'LIKE', '%'.$search_country.'%');
                    }					

                    if (!empty($search_end_date)){

                        $to_date=$search_end_date.' 23:59:59';
                        $convertToDate= $to_date;

                        $query = $query->where(TBL_HOME_SLIDER.".created_at","<=",addslashes($convertToDate));
                    }
                    if(!empty($search_post))
                    {
                        $query = $query->where(TBL_POST.".title", 'LIKE', '%'.$search_post.'%');
                    }
                    if(!empty($search_graph))
                    {
                        $query = $query->where(TBL_SECURITY.".CUSIP", 'LIKE', '%'.$search_graph.'%');
                    }

                    if($search_status == "1" || $search_status == "0")
                    {
                        $query = $query->where(TBL_HOME_SLIDER.".status", $search_status);
                    }                           

                })
            ->make(true);        
    }
}