<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Datatables;
use Illuminate\Validation\Rule;
use App\Models\GraphSlider;
use App\Models\AdminLog;
use App\Models\AdminAction;

class GraphSliderContoller extends Controller
{
    public function __construct()
    {
        $this->moduleRouteText = "cms-graph-sliders";
        $this->moduleViewName = "admin.CMS_Graph_Sliders";
        $this->list_url = route($this->moduleRouteText.".index");

        $module = "CMS Graph Slider";
        $this->module = $module;  

        $this->adminAction= new AdminAction; 
        
        $this->modelObj = new GraphSlider();

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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$LIST_CMS_GRAPH_SLIDER);
        
        if($checkrights) 
        {
            return $checkrights;
        }   

        $data = array();        
        $data['page_title'] = "Manage CMS Graph Sliders";
        $data['graphs'] = \App\Models\CmsGraph::pluck("graph_name","id")->all();         $data['add_url'] = route($this->moduleRouteText.'.create');
        $data['btnAdd'] = \App\Models\Admin::isAccess(\App\Models\Admin::$ADD_CMS_GRAPH_SLIDER);                  
        
        return view($this->moduleViewName.".index", $data);    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$ADD_CMS_GRAPH_SLIDER);
        
        if($checkrights) 
        {
            return $checkrights;
        }

        $data = array();
        $data['graphs'] = \App\Models\CmsGraph::pluck("graph_name","id")->all(); 
        $data['formObj'] = $this->modelObj;
        $data['page_title'] = "Add ".$this->module;
        $data['action_url'] = $this->moduleRouteText.".store";
        $data['action_params'] = 0;
        $data['buttonText'] = "Save";
        $data["method"] = "POST"; 

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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$ADD_CMS_GRAPH_SLIDER);
        
        if($checkrights) 
        {
            return $checkrights;
        }

        $status = 1;
        $msg = $this->addMsg;
        $data = array();
        $lang = $request->get('language');
        
        $valid = Validator::make($request->all(), [
            'date' => 'required',
            'graph_id' => 'required|exists:'.TBL_CMS_GRAPHS.',id',
            'language' => ['required',Rule::in(['english','spanish'])],
        ]);
        if ($valid->fails())
        {
            $messages = $valid->messages();
            
            $status = 0;
            $msg = "";
            
            foreach ($messages->all() as $message) 
            {
                $msg .= $message . "<br />";
            }
        }else{
            if($lang == 'english'){
                $validator = Validator::make($request->all(), [
                    'en_title' => 'required|min:2',
                    'en_description' => 'required|min:5',
                    'language' => ['required',Rule::in(['english'])],
                ]); 
            }
            if($lang == 'spanish'){
                $validator = Validator::make($request->all(), [
                    'sn_title' => 'required|min:2',
                    'sn_description' => 'required|min:5',
                    'date' => 'required',
                    'graph_id' => 'required|exists:'.TBL_CMS_GRAPHS.',id',
                    'language' => ['required',Rule::in(['spanish'])],
                ]);
            }
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
                $lang = $request->get('language');
                $obj = new GraphSlider();
                if($lang == 'english'){
                    $obj->en_title = $request->get('en_title');
                    $obj->en_description = $request->get('en_description');                
                }
                if($lang == 'spanish'){
                    $obj->sn_title = $request->get('sn_title');
                    $obj->sn_description = $request->get('sn_description');                
                }
                    $obj->date = $request->get('date');         
                    $obj->graph_id = $request->get('graph_id');         
                    $obj->save();

                $id = $obj->id;
                //store logs detail
                $params=array();    
                                        
                $params['adminuserid']  = \Auth::guard('admins')->id();
                $params['actionid']     = $this->adminAction->ADD_CMS_GRAPH_SLIDER;
                $params['actionvalue']  = $id;
                $params['remark']       = "Add CMS Graph Slider::".$id;
                                        
                $logs=\App\Models\AdminLog::writeadminlog($params);

                session()->flash('success_message', $msg);           
                
            }
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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$EDIT_CMS_GRAPH_SLIDER);
        
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
        $data['graphs'] = \App\Models\CmsGraph::pluck("graph_name","id")->all(); 
        $data['formObj'] = $formObj;
        $data['page_title'] = "Edit ".$this->module;
        $data['buttonText'] = "Update";
        $data['action_url'] = $this->moduleRouteText.".update";
        $data['action_params'] = $formObj->id;
        $data['method'] = "PUT";

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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$EDIT_CMS_GRAPH_SLIDER);
        
        if($checkrights) 
        {
            return $checkrights;
        }
        $model = $this->modelObj->find($id);
        // check validations
        if(!$model)
        {
            $status = 0;
            $msg = "Record not found !";
        }

        $status = 1;
        $msg = $this->updateMsg;
        $data = array();        
        $lang = $request->get('language');
        
        $valid = Validator::make($request->all(), [
            'date' => 'required',
            'graph_id' => 'required|exists:'.TBL_CMS_GRAPHS.',id',
            'language' => ['required',Rule::in(['english','spanish'])],
        ]);
        if ($valid->fails())
        {
            $messages = $valid->messages();
            
            $status = 0;
            $msg = "";
            
            foreach ($messages->all() as $message) 
            {
                $msg .= $message . "<br />";
            }
        }else{
            if($lang == 'english'){
                $validator = Validator::make($request->all(), [
                    'en_title' => 'required|min:2',
                    'en_description' => 'required|min:5',
                    'language' => ['required',Rule::in(['english'])],
                ]); 
            }
            if($lang == 'spanish'){
                $validator = Validator::make($request->all(), [
                    'sn_title' => 'required|min:2',
                    'sn_description' => 'required|min:5',
                    'language' => ['required',Rule::in(['spanish'])],
                ]);
            }   
        
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
                $lang = $request->get('language');
                $obj = GraphSlider::find($id);

                if($lang == 'english'){
                    $obj->en_title = $request->get('en_title');
                    $obj->en_description = $request->get('en_description');                
                }
                if($lang == 'spanish'){

                    $obj->sn_title = $request->get('sn_title');
                    $obj->sn_description = $request->get('sn_description');                
                }
                    $obj->date = $request->get('date');         
                    $obj->graph_id = $request->get('graph_id');         
                    $obj->save();

                //store logs detail
                    $params=array();
                    
                    $params['adminuserid']  = \Auth::guard('admins')->id();
                    $params['actionid']     = $this->adminAction->EDIT_CMS_GRAPH_SLIDER;
                    $params['actionvalue']  = $id;
                    $params['remark']       = "Edit CMS Graph Slider::".$id;

                    $logs=\App\Models\AdminLog::writeadminlog($params);           
            }
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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$DELETE_CMS_GRAPH_SLIDER);
        
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
                $params['actionid']     = $this->adminAction->DELETE_CMS_GRAPH_SLIDER;
                $params['actionvalue']  = $id;
                $params['remark']       = "Delete CMS Graph Slider::".$id;

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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$LIST_CMS_GRAPH_SLIDER);
        
        if($checkrights) 
        {
            return $checkrights;
        }
     
        $model = GraphSlider::select(TBL_CMS_GRAPH_SLIDERS.".*",TBL_CMS_GRAPHS.".graph_name as graph_name")
                ->join(TBL_CMS_GRAPHS,TBL_CMS_GRAPHS.".id","=",TBL_CMS_GRAPH_SLIDERS.".graph_id");
                         
        return Datatables::eloquent($model)
                        
            ->addColumn('action', function(GraphSlider $row) {
                return view("admin.partials.action",
                    [
                        'currentRoute' => $this->moduleRouteText,
                        'row' => $row,                             
                        'isEdit' => \App\Models\Admin::isAccess(\App\Models\Admin::$EDIT_CMS_GRAPH_SLIDER),
                        'isDelete' => \App\Models\Admin::isAccess(\App\Models\Admin::$DELETE_CMS_GRAPH_SLIDER),                           
                    ]
                    )->render();
            })
            
            ->editColumn('created_at', function($row){
                
                if(!empty($row->created_at))
                    
            return date("j M, Y h:i:s A",strtotime($row->created_at));
                else
                    return '-';    
            })
            ->editColumn('date', function($row){
                
                if(!empty($row->date))
                    
            return date("j M, Y",strtotime($row->date));
                else
                    return '-';    
            })
            ->rawColumns(['action'])                    
            ->filter(function ($query) 
            {
                $search_start_date = trim(request()->get("search_start_date"));
                $search_end_date = trim(request()->get("search_end_date")); 
                $search_graph = request()->get("search_graph");
                $search_title = request()->get("search_title"); 
                $search_location = request()->get("search_location"); 

                if (!empty($search_start_date)){

                    $from_date=$search_start_date.' 00:00:00';
                    $convertFromDate= $from_date;

                    $query = $query->where(TBL_CMS_GRAPH_SLIDERS.".created_at",">=",addslashes($convertFromDate));
                }
                if (!empty($search_end_date)){

                    $to_date=$search_end_date.' 23:59:59';
                    $convertToDate= $to_date;

                    $query = $query->where(TBL_CMS_GRAPH_SLIDERS.".created_at","<=",addslashes($convertToDate));
                }

                if(!empty($search_title))
                {
                    $query = $query->where(TBL_CMS_GRAPH_SLIDERS.".en_title", 'LIKE', '%'.$search_title.'%');
                }

                if(!empty($search_graph))
                {
                    $query = $query->where(TBL_CMS_GRAPHS.".id",$search_graph);
                }
                if(!empty($search_location))
                {
                    $query = $query->where(TBL_CMS_GRAPHS.".location",$search_location);
                }                
                
            })
            ->make(true);        
    }
}
