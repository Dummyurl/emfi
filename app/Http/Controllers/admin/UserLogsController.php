<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\UserLog;
use App\Models\AdminAction;
use Datatables;


class UserLogsController extends Controller {

    public function __construct() {
        
        $this->moduleRouteText = "user-logs";
        $this->moduleViewName = "admin.UserLogs";
        $this->list_url = route($this->moduleRouteText.".index");

        $module = "UserLog";
        $this->module = $module;  

        $this->adminAction= new AdminAction;

        $this->modelObj = new \App\Models\UserLog;  

        view()->share("list_url", $this->list_url);
        view()->share("moduleRouteText", $this->moduleRouteText);
        view()->share("moduleViewName", $this->moduleViewName);

    }/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$LIST_USER_LOGS);
        
        if($checkrights) 
        {
            return $checkrights;
        }

        $data = array();        
        $data['page_title'] = "Manage UserLog"; 
        $data['users'] = \App\Models\User::pluck("name","id")->all();
        $data['userAction'] = \App\Models\UserAction::pluck("description","id")->all();
        return view($this->moduleViewName.".index", $data);         
    }
    public function data(Request $requesst)
    {
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$LIST_USER_LOGS);
        
        if($checkrights) 
        {
            return $checkrights;
        }

        $model = UserLog::select(TBL_USER_LOGS.".*",TBL_USERS.".firstname",TBL_USERS.".lastname",TBL_USERS_ACTION.".description")
                ->join(TBL_USERS,TBL_USERS.".id",'=',TBL_USER_LOGS.".id")
                ->join(TBL_USERS_ACTION,TBL_USERS_ACTION.".id",'=',TBL_USER_LOGS.".actionid");
        
            return Datatables::eloquent($model)

            ->editColumn('created_at', function($row){
                
                if(!empty($row->created_at))         
                    return date("j M, Y h:i:s A",strtotime($row->created_at));
                else
                    return '-';    
            })
                
            ->filter(function ($query) 
            {
                $search_start_date = trim(request()->get("search_start_date"));                    
                $search_end_date = trim(request()->get("search_end_date"));
                $search_id = request()->get("search_id");
                $search_userid = request()->get("search_userid");
                $search_actionid = request()->get("search_actionid");
                $search_action_value = request()->get("search_action_value");
                $search_remark = request()->get("search_remark");
                $search_address = request()->get("search_address");
                $search_ids = request()->get("search_ids");
                $search_admin_name = request()->get("search_admin_name");
                $search_distinct_user = request()->get("search_distinct_user");

                if (!empty($search_start_date)){

                    $from_date=$search_start_date.' 00:00:00';
                    $convertFromDate= $from_date;

                    $query = $query->where(TBL_USER_LOGS.".created_at",">=",addslashes($convertFromDate));
                }
                if (!empty($search_end_date)){

                    $to_date=$search_end_date.' 23:59:59';
                    $convertToDate= $to_date;

                    $query = $query->where(TBL_USER_LOGS.".created_at","<=",addslashes($convertToDate));
                }
                if(!empty($search_id))
                {
                    $idArr = explode(',', $search_id);
                    $idArr = array_filter($idArr);                
                    if(count($idArr)>0)
                    {
                        $query = $query->whereIn(TBL_USER_LOGS.".id",$idArr);
                    } 
                } 

            })->make(true);     
    } 

    
}
