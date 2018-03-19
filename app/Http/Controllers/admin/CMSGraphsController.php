<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Datatables;
use Illuminate\Validation\Rule;
use App\Models\CmsGraph;
use App\Models\AdminLog;
use App\Models\AdminAction;

class CMSGraphsController extends Controller
{
    public function __construct() {

        $this->moduleRouteText = "cms-graphs";
        $this->moduleViewName = "admin.CMS_Graphs";
        $this->list_url = route($this->moduleRouteText.".index");

        $module = "CMS Graph Details";
        $this->module = $module;  

        $this->adminAction= new AdminAction; 
        
        $this->modelObj = new CmsGraph();  

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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$LIST_CMS_GRAPH);
        
        if($checkrights) 
        {
            return $checkrights;
        }   

        $data = array();        
        $data['page_title'] = "Manage CMS Graphs";
        $data['pages'] = \App\Models\CmsPage::pluck("title","id")->all();
        $data['cities'] = \App\Models\City::pluck("title","id")->all();
        $data['add_url'] = route($this->moduleRouteText.'.create');
        $data['btnAdd'] = \App\Models\Admin::isAccess(\App\Models\Admin::$ADD_CMS_GRAPH);                  
        
        return view($this->moduleViewName.".index", $data);    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$ADD_CMS_GRAPH);
        
        if($checkrights) 
        {
            return $checkrights;
        }

        $data = array();
        $data['pages'] = \App\Models\CmsPage::pluck("title","id")->all();
        $data['cities'] = \App\Models\City::pluck("title","id")->all();
        $data['positions'] = [1=>'One', 2=>'Two',3=>'Three'];
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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$ADD_CMS_GRAPH);
        
        if($checkrights) 
        {
            return $checkrights;
        }

        $status = 1;
        $msg = $this->addMsg;
        $data = array();

        $message = ['cms_page_id.unique'=>'CMS Page already exists !'];
        $validator = Validator::make($request->all(), [
            'graph_name' => 'required|min:2',
            'location' => 'required|exists:'.TBL_CITY.',id',
            'cms_page_id' => 'required|exists:'.TBL_CMS_PAGES.',id|unique:'.TBL_CMS_GRAPHS.',cms_page_id',
            'position' => ['required',Rule::in([1,2,3])],
        ],$message);
        
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
            $input = $request->all();
            $obj = $this->modelObj->create($input);
            $id = $obj->id;
            //store logs detail
            $params=array();    
                                    
            $params['adminuserid']  = \Auth::guard('admins')->id();
            $params['actionid']     = $this->adminAction->ADD_CMS_GRAPH;
            $params['actionvalue']  = $id;
            $params['remark']       = "Add CMS Graph Details::".$id;
                                    
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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$EDIT_CMS_GRAPH);
        
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
        $data['pages'] = \App\Models\CmsPage::pluck("title","id")->all(); 
        $data['cities'] = \App\Models\City::pluck("title","id")->all(); 
        $data['positions'] = [1=>'One', 2=>'Two',3=>'Three'];
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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$EDIT_CMS_GRAPH);
        
        if($checkrights) 
        {
            return $checkrights;
        }
        $model = $this->modelObj->find($id);

        $status = 1;
        $msg = $this->updateMsg;
        $data = array();        
        
        $message = ['cms_page_id.unique'=>'CMS Page already exists !'];
        $validator = Validator::make($request->all(), [          
            'graph_name' => 'required|min:2',
            'location' => 'required|exists:'.TBL_CITY.',id',
            'cms_page_id' => 'required|exists:'.TBL_CMS_PAGES.',id|unique:'.TBL_CMS_GRAPHS.',cms_page_id,'.$id,
            'position' => ['required',Rule::in([1,2,3])],
        ],$message);
        
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
            $input = $request->all();
            $model->update($input); 

            //store logs detail
                $params=array();
                
                $params['adminuserid']  = \Auth::guard('admins')->id();
                $params['actionid']     = $this->adminAction->EDIT_CMS_GRAPH;
                $params['actionvalue']  = $id;
                $params['remark']       = "Edit CMS Graph Details::".$id;

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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$DELETE_CMS_GRAPH);
        
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
                $params['actionid']     = $this->adminAction->DELETE_CMS_GRAPH;
                $params['actionvalue']  = $id;
                $params['remark']       = "Delete CMS Graph Details::".$id;

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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$LIST_CMS_GRAPH);
        
        if($checkrights) 
        {
            return $checkrights;
        }
     
        $model = CmsGraph::select(TBL_CMS_GRAPHS.".*",TBL_CMS_PAGES.".title as cms_page",TBL_CITY.".title as city")
                ->join(TBL_CMS_PAGES,TBL_CMS_PAGES.".id","=",TBL_CMS_GRAPHS.".cms_page_id")
                ->join(TBL_CITY,TBL_CITY.".id","=",TBL_CMS_GRAPHS.".location");
                         
        return Datatables::eloquent($model)
                        
            ->addColumn('action', function(CmsGraph $row) {
                return view("admin.partials.action",
                    [
                        'currentRoute' => $this->moduleRouteText,
                        'row' => $row,                             
                        'isEdit' => \App\Models\Admin::isAccess(\App\Models\Admin::$EDIT_CMS_GRAPH),
                        'isDelete' => \App\Models\Admin::isAccess(\App\Models\Admin::$DELETE_CMS_GRAPH),                           
                    ]
                    )->render();
            })
            
            ->editColumn('created_at', function($row){
                
                if(!empty($row->created_at))                    
                    
            return date("j M, Y h:i:s A",strtotime($row->created_at));
                else
                    return '-';    
            })
            ->editColumn('position', function($row){
                $pos ='';
                if($row->position == 1)
                    $pos = "One";
                if($row->position == 2)
                    $pos = "Two";
                if($row->position == 3)
                    $pos = "Three";
                return $pos;    
            })
            ->rawColumns(['action'])                    
            ->filter(function ($query) 
            {
                $search_start_date = trim(request()->get("search_start_date"));
                $search_end_date = trim(request()->get("search_end_date")); 
                $search_graph = request()->get("search_graph");
                $search_cms = request()->get("search_cms"); 
                $search_location = request()->get("search_location"); 

                if (!empty($search_start_date)){

                    $from_date=$search_start_date.' 00:00:00';
                    $convertFromDate= $from_date;

                    $query = $query->where(TBL_CMS_GRAPHS.".created_at",">=",addslashes($convertFromDate));
                }
                if (!empty($search_end_date)){

                    $to_date=$search_end_date.' 23:59:59';
                    $convertToDate= $to_date;

                    $query = $query->where(TBL_CMS_GRAPHS.".created_at","<=",addslashes($convertToDate));
                }

                if(!empty($search_graph))
                {
                    $query = $query->where(TBL_CMS_GRAPHS.".graph_name", 'LIKE', '%'.$search_graph.'%');
                }

                if(!empty($search_cms))
                {
                    $query = $query->where(TBL_CMS_PAGES.".id",$search_cms);
                }
                if(!empty($search_location))
                {
                    $query = $query->where(TBL_CITY.".id",$search_location);
                }                
                
            })
            ->make(true);        
    } 
}
