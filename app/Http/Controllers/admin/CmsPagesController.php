<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Datatables;
use App\Models\CmsPage;
use App\Models\AdminLog;
use App\Models\AdminAction;

class CmsPagesController extends Controller
{
    public function __construct()
    {
        $this->moduleRouteText = "cms-pages";
        $this->moduleViewName = "admin.CMS_Pages";
        $this->list_url = route($this->moduleRouteText.".index");

        $module = "Page";
        $this->module = $module;  

        $this->adminAction= new AdminAction; 
        
        $this->modelObj = new CmsPage();

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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$LIST_CMS_PAGES);
        
        if($checkrights) 
        {
            return $checkrights;
        }   

        $data = array();        
        $data['page_title'] = "Manage Pages";

        $data['add_url'] = route($this->moduleRouteText.'.create');
        $data['btnAdd'] = \App\Models\Admin::isAccess(\App\Models\Admin::$ADD_CMS_PAGES);                  
        
        return view($this->moduleViewName.".index", $data);    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$ADD_CMS_PAGES);
        
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
        $data['languages']= \App\Custom::getLanguages();

        return view($this->moduleViewName.'.cmsAdd', $data); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$LIST_CMS_PAGES);
        
        if($checkrights) 
        {
            return $checkrights;
        }
        $status = 1;
        $msg = $this->addMsg;
        $data = array();

        $validator = Validator::make($request->all(), [
            'page_constant' => 'required|min:2|unique:'.TBL_CMS_PAGES.',page_constant',     
            'title.*.*' => 'required|min:2',     
            'description.*.*' => 'required|min:2',
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
            $name = $request->get('page_constant');
            $title = $request->get('title');
            $description = $request->get('description');
               
            $obj = new \App\Models\CmsPage();
            $obj->page_constant = $name;
            $obj->save();

            $languages = \App\Custom::getLanguages();
            foreach ($languages as $locale => $val)
            {
                if(is_array($title) && !empty($title))
                {
                    $titles = isset($title[$locale][0]) ? $title[$locale][0] : '';

                    $obj->translateOrNew($locale)->title = $titles;
                }
                if(is_array($description) && !empty($description))
                {   
                    $desc = isset($description[$locale][0]) ? $description[$locale][0] : '';
                    $obj->translateOrNew($locale)->description = $desc;
                }
            }
            $obj->save();
            
            $id = $obj->id;
            //store logs detail
            $params=array();    
                                    
            $params['adminuserid']  = \Auth::guard('admins')->id();
            $params['actionid']     = $this->adminAction->ADD_CMS_PAGES;
            $params['actionvalue']  = $id;
            $params['remark']       = "Add CMS Page::".$id;
                                    
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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$EDIT_CMS_PAGES);
        
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
        $data['languages']= \App\Custom::getLanguages();

        return view($this->moduleViewName.'.cmsAdd', $data);
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
        $model = $this->modelObj->find($id);

        $status = 1;
        $msg = $this->updateMsg;
        $data = array();        
        
        $validator = Validator::make($request->all(), [          
            //'name' => 'required|min:2|unique:'.TBL_CMS_PAGES.',name,'.$id,     
            'title.*.*' => 'required|min:2',     
            'description.*.*' => 'required|min:2',    
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
            //$name = $request->get('name');
            $title = $request->get('title');
            $description = $request->get('description');
               
            //$model->name = $name;
            //$model->save();

            $languages = \App\Custom::getLanguages();
            foreach ($languages as $locale => $val)
            {
                if(is_array($title) && !empty($title))
                {
                    $titles = isset($title[$locale][0]) ? $title[$locale][0] : '';

                    $model->translateOrNew($locale)->title = $titles;
                }
                if(is_array($description) && !empty($description))
                {   
                    $desc = isset($description[$locale][0]) ? $description[$locale][0] : '';
                    $model->translateOrNew($locale)->description = $desc;
                }
            }
            $model->save();
            
            //store logs detail
                $params=array();
                
                $params['adminuserid']  = \Auth::guard('admins')->id();
                $params['actionid']     = $this->adminAction->EDIT_CMS_PAGES;
                $params['actionvalue']  = $id;
                $params['remark']       = "Edit CMS Page::".$id;

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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$DELETE_CMS_PAGES);
        
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
                $trans = \App\Models\CmsPageTranslation::where('cms_page_id',$id);
                if($trans){
                    $trans->delete();
                }
                $modelObj->delete();
                session()->flash('success_message', $this->deleteMsg); 

                //store logs detail
                $params=array();
                
                $params['adminuserid']  = \Auth::guard('admins')->id();
                $params['actionid']     = $this->adminAction->DELETE_CMS_PAGES;
                $params['actionvalue']  = $id;
                $params['remark']       = "Delete CMS Page::".$id;

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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$LIST_CMS_PAGES);
        
        if($checkrights) 
        {
            return $checkrights;
        }
     
        $model = CmsPage::query();
                         
        return Datatables::eloquent($model)
                       
            
            ->addColumn('action', function(CmsPage $row) {
                return view("admin.partials.action",
                    [
                        'currentRoute' => $this->moduleRouteText,
                        'row' => $row,                             
                        'isEdit' => \App\Models\Admin::isAccess(\App\Models\Admin::$EDIT_CMS_PAGES),
                        'isDelete' => \App\Models\Admin::isAccess(\App\Models\Admin::$DELETE_CMS_PAGES),                          
                    ]
                    )->render();
            })
            
            ->editColumn('created_at', function($row){
                
                if(!empty($row->created_at))                    
                    
            return date("j M, Y h:i:s A",strtotime($row->created_at));
                else
                    return '-';    
            })
            ->rawColumns(['action'])                    
            ->filter(function ($query) 
            {
                $search_start_date = trim(request()->get("search_start_date"));
                $search_end_date = trim(request()->get("search_end_date")); 
                $search_id = request()->get("search_id");                                   
                $search_text = request()->get("search_text");

                if (!empty($search_start_date)){

                    $from_date=$search_start_date.' 00:00:00';
                    $convertFromDate= $from_date;

                    $query = $query->where(TBL_CMS_PAGES.".created_at",">=",addslashes($convertFromDate));
                }
                if (!empty($search_end_date)){

                    $to_date=$search_end_date.' 23:59:59';
                    $convertToDate= $to_date;

                    $query = $query->where(TBL_CMS_PAGES.".created_at","<=",addslashes($convertToDate));
                }
                               
                if(!empty($search_id))
                {
                    $idArr = explode(',', $search_id);
                    $idArr = array_filter($idArr);                
                    if(count($idArr)>0)
                    {
                        $query = $query->whereIn(TBL_CMS_PAGES.".id",$idArr);
                    } 
                }

                if(!empty($search_text))
                {
                    $query = $query->where(TBL_CMS_PAGES.".page_constant", 'LIKE', '%'.$search_text.'%');
                }                
                
            })
            ->make(true);        
    } 
}
