<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Datatables;
use App\Models\Country;
use App\Models\Securities;
use App\models\AdminLog;
use App\Models\AdminAction;
use Illuminate\Validation\Rule;

class CountriesController extends Controller
{
    public function __construct() {

        $this->moduleRouteText = "countries";
        $this->moduleViewName = "admin.countries";
        $this->list_url = route($this->moduleRouteText.".index");

        $module = "Country";
        $this->module = $module;  

        $this->adminAction= new AdminAction; 
        
        $this->modelObj = new Country();  

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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$LIST_COUNTRIES);
        
        if($checkrights) 
        {
            return $checkrights;
        }
         
        $data = array();        
        $data['page_title'] = "Manage Countries";
        $data['add_url'] = route($this->moduleRouteText.'.create');
        $data['btnAdd'] = \App\Models\Admin::isAccess(\App\Models\Admin::$ADD_COUNTRIES);                  
        
        return view($this->moduleViewName.".index", $data);    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$ADD_COUNTRIES);
        
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
        $data['languages']= \App\Custom::getLanguages();
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

        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$ADD_COUNTRIES);
        
        if($checkrights) 
        {
            return $checkrights;
        }

        $status = 1;
        $msg = $this->addMsg;
        $data = array();

        $rules = [
            'title.en.min'=>'English title is min 2 character!',
            'title.en.required'=>'English title is required!',
            'title.es.min'=>'Spanish title is min 2 character!',
            'title.es.required'=>'Spanish title is required!',
            ];

        $validator = Validator::make($request->all(), [
            'country_code' => 'required|unique:countries,country_code|min:2',
            'country_type' => ['required', Rule::in([1,2])],
            'title.en' => 'required|min:2',
            'title.es' => 'required|min:2',
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
            $title = $request->get('title');
            $country_code = $request->get('country_code');
            $country_type = $request->get('country_type');
            
            $obj = new Country();
            $obj->title = isset($title['en']) ? $title['en'] : '';
            $obj->country_code = $country_code;
            $obj->country_type = $country_type;
            $obj->save();

            $languages = \App\Custom::getLanguages();
            foreach ($languages as $locale => $val)
            {
                $val = isset($title[$locale]) ? $title[$locale] : '';
                $obj->translateOrNew($locale)->country_name = $val;
            }
            $obj->save();

            $id = $obj->id;
            //store logs detail
            $params=array();    
                                    
            $params['adminuserid']  = \Auth::guard('admins')->id();
            $params['actionid']     = $this->adminAction->ADD_COUNTRIES ;
            $params['actionvalue']  = $id;
            $params['remark']       = "Add Country::".$id;
                                    
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
        
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$EDIT_COUNTRIES);
        
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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$EDIT_COUNTRIES);
        
        if($checkrights) 
        {
            return $checkrights;
        }

        $model = $this->modelObj->find($id);

        $status = 1;
        $msg = $this->updateMsg;
        $data = array();        
        
        $rules = [
            'title.en.min'=>'English title is min 2 character!',
            'title.en.required'=>'English title is required!',
            'title.es.min'=>'Spanish title is min 2 character!',
            'title.es.required'=>'Spanish title is required!',
            ];   
        $validator = Validator::make($request->all(), [
            'country_code' => 'required|min:2|unique:countries,country_code,'.$id,
            'country_type' => ['required', Rule::in([1,2])],
            'title.en' => 'required|min:2',
            'title.es' => 'required|min:2',
        ],$rules);
        
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
            $title = $request->get('title');
            $country_code = $request->get('country_code');
            $country_type = $request->get('country_type');
            
            $model->title = isset($title['en']) ? $title['en'] : '';
            $model->country_code = $country_code;
            $model->country_type = $country_type;
            $model->save();

            $languages = \App\Custom::getLanguages();
            foreach ($languages as $locale => $val)
            {
                $val = isset($title[$locale]) ? $title[$locale] : '';
                $model->translateOrNew($locale)->country_name = $val;
            }
            $model->save();

            $country_type = $request->get('country_type');
            if($country_type){
                \DB::table('securities')->where('country_id', $id)->update(['country_type' => $country_type]);
            }

            //store logs detail
                $params=array();
                
                $params['adminuserid']  = \Auth::guard('admins')->id();
                $params['actionid']     = $this->adminAction->EDIT_COUNTRIES;
                $params['actionvalue']  = $id;
                $params['remark']       = "Edit Country::".$id;

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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$DELETE_COUNTRIES);
        
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
                $trans = \App\Models\CountryTranslation::where('country_id',$id);
                if($trans)
                {
                    $trans->delete();
                }
                $modelObj->delete();
                session()->flash('success_message', $this->deleteMsg); 

                //store logs detail
                $params=array();
                
                $params['adminuserid']  = \Auth::guard('admins')->id();
                $params['actionid']     = $this->adminAction->DELETE_COUNTRIES;
                $params['actionvalue']  = $id;
                $params['remark']       = "Delete Country::".$id;

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
        $checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$LIST_COUNTRIES);
        
        if($checkrights) 
        {
            return $checkrights;
        }
     
        $model = Country::query();
                         
        return Datatables::eloquent($model)
            
            ->addColumn('action', function(Country $row) {
                return view("admin.partials.action",
                    [
                        'currentRoute' => $this->moduleRouteText,
                        'row' => $row,                             
                        'isEdit' => \App\Models\Admin::isAccess(\App\Models\Admin::$EDIT_COUNTRIES),
                                                     
                    ]
                    )->render();
            })
            
            ->editColumn('created_at', function($row){
                
                if(!empty($row->created_at))                    
                    
            return date("j M, Y h:i:s A",strtotime($row->created_at));
                else
                    return '-';    
            })

            ->editColumn('country_type', function($row){
                
                $return =  EMERGING_COUNTRY;
                if($row->country_type == 1){
                    $return =  DEVELOPED_COUNTRY;
                }
                return $return;
            })
                                
            ->filter(function ($query) 
            {
                $search_start_date = trim(request()->get("search_start_date"));
                $search_end_date = trim(request()->get("search_end_date")); 
                $search_id = request()->get("search_id");                                   
                $search_country = request()->get("search_country");
                $search_code = request()->get("search_code");
                $search_type = request()->get("search_type");

                if (!empty($search_start_date)){

                    $from_date=$search_start_date.' 00:00:00';
                    $convertFromDate= $from_date;

                    $query = $query->where("created_at",">=",addslashes($convertFromDate));
                }
                if (!empty($search_end_date)){

                    $to_date=$search_end_date.' 23:59:59';
                    $convertToDate= $to_date;

                    $query = $query->where("created_at","<=",addslashes($convertToDate));
                }
                
                if(!empty($search_country))
                {
                    $query = $query->where(TBL_COUNTRY.".title", 'LIKE', '%'.$search_country.'%');
                }
                if(!empty($search_code))
                {
                    $query = $query->where(TBL_COUNTRY.".country_code", 'LIKE', '%'.$search_code.'%');
                }
                if($search_type == "1" || $search_type == "2")
                {
                    $query = $query->where(TBL_COUNTRY.".country_type", $search_type);
                }
                if(!empty($search_id))
                {
                    $idArr = explode(',', $search_id);
                    $idArr = array_filter($idArr);                
                    if(count($idArr)>0)
                    {
                        $query = $query->whereIn('id',$idArr);
                    } 
                }
                
            })
            ->make(true);        
    } 
}
