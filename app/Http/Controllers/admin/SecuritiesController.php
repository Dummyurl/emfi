<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Models\MarketType;
use App\Models\Securities;
use App\Models\HistoricalData;
use App\Models\BondHistoricalData;
use App\Models\AdminAction;
use Excel;
use Validator;
use Datatables;

class SecuritiesController extends Controller
{
	public function massupload()
	{
		$checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$EXCEL_IMPORT_GRAPH);

        if($checkrights)
        {
            return $checkrights;
        }
        $data['page_title'] = "Upload Data Excel";
        $data['validate_url'] = url('admin/validate');
        $data['buttonText '] = "Upload Excel/CSV";

        return view('admin.uploadExcel.massupload', $data);
	}
    public function upload()
    {
		$checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$EXCEL_IMPORT_GRAPH);

        if($checkrights)
        {
            return $checkrights;
        }
        $data['page_title'] = "Upload Data";
        $data['validate_url'] = url('admin/validate');
        $data['buttonText '] = "Upload";

        return view('admin.uploadExcel.upload', $data);
    }

	public function index()
	{
		$checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$LIST_SECURITY);

        if($checkrights)
        {
            return $checkrights;
        }
		$data = [];
		$data['page_title'] = "Manage Securities";
		$data['MarketType'] = MarketType::getArrayList();
		$data['benchmark_family_list'] = Securities::where('benchmark_family', "!=", "")
												   ->groupBy('benchmark_family')
												   ->pluck("benchmark_family","benchmark_family")->all();

		//dd($data['benchmark_family_list']);
		return view('admin.uploadExcel.index', $data);
	}

	public function data(Request $request)
	{
		$checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$LIST_SECURITY);

        if($checkrights)
        {
            return $checkrights;
        }
        
		$model = Securities::select('securities.*','market_type.market_name')
						   ->join('market_type', 'securities.market_id','=','market_type.id');

		return Datatables::eloquent($model)
						 ->addColumn('action',  function ($row){
							 $html = '';
							 $benchmark_family = 0;
							 if(!empty($row->benchmark_family)){
								$benchmark_family = $row->benchmark_family;
							 }
							 $url = route('edit-security-data',['id' => $row->id]);
							 $str = "'$row->id','$benchmark_family','$row->benchmark'";
							 if(\App\Models\Admin::isAccess(\App\Models\Admin::$EDIT_SECURITY)){
							 $html .='<div class="btn-group">';
							 $html .= "<a title='Change Default Status' class='btn btn-primary btn-xs' onclick=\"edit(".$str.");\"><i class='
fa fa-check-square-o'></i></a>";
							$html .="<a class='btn btn-success btn-xs' title='Edit' href='".$url."'><i class='fa fa-edit'></i></a></div>";
}

							return $html;
						 })
						->addColumn('default',  function ($row){
							if($row->default == 1)
								return '<a class="btn btn-success btn-xs">Yes</a>';
							else
								return '';
						})
						 ->rawColumns(['action','default'])
						 ->filter( function ($query){
							 $search_cusip = request()->get('search_cusip');
							 $search_market = request()->get('search_market');
							 $search_status = request()->get('search_status');

							 if (!empty($search_cusip)) {
							 	$query = $query->where('securities.CUSIP',"LIKE", '%'.$search_cusip.'%');
							 }
							 if (!empty($search_market)) {
							 	$query = $query->where('securities.market_id', '=', $search_market);
							 }
							if($search_status == "1" || $search_status == "0")
                    		{
                        		$query = $query->where("default", $search_status);
                    		}
						 })
						 ->make(true);
	}

	public function update(Request $request , $id)
	{
		$checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$EDIT_SECURITY);

        if($checkrights)
        {
            return $checkrights;
        }

		$status = 1;
		$msg = 'record has been updated !';


		$new_benchmark = $request->get('new_benchmark');
		$select_benchmark = $request->get('select_benchmark');
		$set_benchmark = $request->get('set_benchmark');
		$obj = Securities::find($id);
		if(!$obj){
			$status = 0;
			$msg = 'record not found !';
			return ['status' => $status, 'msg'=>$msg];
		}
		else{
			if(empty($new_benchmark) && empty($select_benchmark)){
				$status = 0;
				$msg = 'please enter at least one benchmark!';
				return ['status' => $status, 'msg'=>$msg];
			}
			elseif (!empty($new_benchmark) && !empty($select_benchmark)) {
				$status = 0;
				$msg = 'Please enter only one benchmark';
				return ['status' => $status, 'msg'=>$msg];
			}
			else {
				if (isset($new_benchmark) && !empty($new_benchmark)) {
					$obj->benchmark_family = $new_benchmark;
				}
				elseif (isset($select_benchmark) && !empty($select_benchmark)) {
					$obj->benchmark_family = $select_benchmark;
				}
			}

			if ($set_benchmark == 'on') {
				$obj->benchmark = 1;
 			}
			else {
				$obj->benchmark = 0;
			}
			$obj->save();
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
		return ['status' => $status, 'msg'=>$msg];
	}


    public function validateexcel(Request $request)
    {
     	$checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$EXCEL_IMPORT_GRAPH);

		if($checkrights)
		{
			return $checkrights;
		}

		$status = 1;
		$msg = "Your data was successfully added";
		$data = [];
		$created_date = '';
		$last_upload_date = date("Y-m-d");
		$TZ = 'America/New_York';
		$uploaded_date = $request->get('uploaded_date');
		if(isset($uploaded_date) && !empty($uploaded_date)){
			$created_date = $uploaded_date;
			$query = "SELECT MAX(securities.`created`) as last_upload_date FROM securities";
			$arr = \DB::select($query);
			if(!empty($arr)){
				$last_upload_date = $arr[0]->last_upload_date;
			}
			if($uploaded_date >= $last_upload_date && date("Y-m-d", strtotime($uploaded_date) ) != date("Y-m-d"))
			{
				$created_date = $uploaded_date;
				$updated_date = [ 0 => $created_date];
				WriteJsonInFile($updated_date, GET_LAST_UPDATED_DATE);
			} else if (date("Y-m-d", strtotime($uploaded_date)) == date("Y-m-d"))
			{
				$dd = date("Y-m-d H:i:s");
				$created_date =  convertDateFromTimezone($dd,SET_TIMEZONE_FOR_UPLOAD_DATE_UTC,SET_TIMEZONE_FOR_UPLOAD_DATE,'Y-m-d');
				$updated_date = [ 0 => $created_date];
				WriteJsonInFile($updated_date, GET_LAST_UPDATED_DATE);
			}
		} else {
			$dd = date("Y-m-d H:i:s");
			$created_date =  convertDateFromTimezone($dd,SET_TIMEZONE_FOR_UPLOAD_DATE_UTC,SET_TIMEZONE_FOR_UPLOAD_DATE,'Y-m-d');
			$updated_date = [ 0 => $created_date];
			WriteJsonInFile($updated_date, GET_LAST_UPDATED_DATE);
		}

		$validator = Validator::make($request->all(), [
			'excelToUpload' => 'required|excel'
		], ['excelToUpload.required' => 'Please upload a CSV file.' , 'excelToUpload.excel' => 'It must be a CSV file.']);

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
			$uploaded_date = $request->get('uploaded_date');
			if ($request->hasFile('excelToUpload'))
			{
				$csv_file = $request->file('excelToUpload');
				$size = $_FILES['excelToUpload']['size'];
				$ext = $csv_file->getClientOriginalExtension();
				$markets = MarketType::pluck('id','market_name');
				$countries = \DB::table('countries')->pluck('id','country_code')->toArray();
				$filename = time().".".$ext;
				$uploadPath = 'uploads' . DIRECTORY_SEPARATOR . 'csv_files'.DIRECTORY_SEPARATOR;
				$csv_file->move($uploadPath, $filename);
				$file = fopen($uploadPath.$filename,"r");
				$i = 0;
				$bytes = ftell($file);
				$fields = [];

				while(! feof($file))
				{
					$idata = [];
					$data =  fgetcsv($file);
					if (empty($data)){
						break;
					}
					if ($bytes >= $size) {
						break;
					}
					if ($i == 0)
					{
						foreach ($data as $key => $value)
						{
							if(!empty($value)){
								$value = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value);
								$fields[trim(strtolower($value))] = $key;
							}
						}
						// echo "<pre>";
						// print_r($fields);
						// exit();
					}
					else
					{
						// Array for historical data
						$hdata = [];
						$idata['created'] = $created_date;
						$idata['rtg_sp'] = ($data[$fields['rtg_sp']] == "#N/A N/A" || !isset($data[$fields['rtg_sp']])) ? '' : str_replace(',','',$data[$fields['rtg_sp']]);
						$idata['current_oecd_member_cor_class'] = ($data[$fields['current_oecd_member_cor_class']] == "#N/A N/A" || !isset($data[$fields['current_oecd_member_cor_class']])) ? '' : str_replace(',','',$data[$fields['current_oecd_member_cor_class']]);
						$idata['market_size'] = ($data[$fields['market_size']] == "#N/A N/A" || !isset($data[$fields['market_size']])) ? '' : str_replace(',','',$data[$fields['market_size']]);
						$idata['volume'] = ($data[$fields['volume']] == "#N/A N/A" || !isset($data[$fields['volume']])) ? '' : str_replace(',','',$data[$fields['volume']]);

						$idata['bid_price'] = ($data[$fields['px_bid']] == "#N/A N/A" || !isset($data[$fields['px_bid']])) ? '' : str_replace(',','',$data[$fields['px_bid']]);
						$idata['ask_price'] = ($data[$fields['px_ask']] == "#N/A N/A" || !isset($data[$fields['px_ask']])) ? '' : str_replace(',','',$data[$fields['px_ask']]);
						$idata['last_price'] = ($data[$fields['px_last']] == "#N/A N/A" || !isset($data[$fields['px_last']])) ? '' : str_replace(',','',$data[$fields['px_last']]);
						$idata['low_price'] = ($data[$fields['px_low']] == "#N/A N/A" || !isset($data[$fields['px_low']])) ? '' : str_replace(',','',$data[$fields['px_low']]);
						$idata['high_price'] = ($data[$fields['px_high']] == "#N/A N/A" || !isset($data[$fields['px_high']])) ? '' : str_replace(',','',$data[$fields['px_high']]);
						$idata['net_change'] = ($data[$fields['chg_net_1d']] == "#N/A N/A" || !isset($data[$fields['chg_net_1d']])) ? '' : str_replace(',','',$data[$fields['chg_net_1d']]);
						$idata['percentage_change'] = ($data[$fields['chg_pct_1d']] == "#N/A N/A" || !isset($data[$fields['chg_pct_1d']])) ? '' : $data[$fields['chg_pct_1d']];

						$idata['net_change'] = str_replace('(', "-", $idata['net_change']);
						$idata['net_change'] = str_replace(')', "", $idata['net_change']);

						$idata['percentage_change'] = str_replace('(', "-", $idata['percentage_change']);
						$idata['percentage_change'] = str_replace(')', "", $idata['percentage_change']);
						// Only historical_data table's colums will be added to this array.
						$hdata = $idata;
						$idata['CUSIP'] = ($data[$fields['cusip']] == "#N/A N/A" || !isset($data[$fields['cusip']])) ? "" : $data[$fields['cusip']];

						$idata['yld_ytm_mid'] = ($data[$fields['yld_ytm_mid']] == "#N/A N/A" || !isset($data[$fields['yld_ytm_mid']])) ? '' : $data[$fields['yld_ytm_mid']];
						$idata['z_sprd_mid'] = ($data[$fields['z_sprd_mid']] == "#N/A N/A" || !isset($data[$fields['z_sprd_mid']])) ? '' : $data[$fields['z_sprd_mid']];
						$idata['dur_adj_mid'] = ($data[$fields['dur_adj_mid']] == "#N/A N/A" || !isset($data[$fields['dur_adj_mid']])) ? '' : $data[$fields['dur_adj_mid']];
						
						$idata['market_id'] = '';
						if(isset($data[$fields['market']]) && $data[$fields['market']] != "#N/A N/A" && !empty($data[$fields['market']]))
						{
							$idata['market_id'] = $markets[$data[$fields['market']]];
						}

						$idata['country'] = ($data[$fields['country']] == "#N/A N/A" || !isset($data[$fields['country']])) ? '' : $data[$fields['country']];


						
						$idata['country_id'] = '';
						if(isset($data[$fields['country']]) && $data[$fields['country']] != "#N/A N/A" && !empty($data[$fields['country']]))
						{
							$countries = \App\Models\Country::where('country_code',$data[$fields['country']])->first();
							if($countries){
	                            $country_id = $countries->id;
							} else {
								$cc = new \App\Models\Country();
	                            $cc->country_code = $data[$fields['country']];
	                            $cc->save();
	                            $country_id = $cc->id;
							}
							$idata['country_id'] = $country_id;
						}
						$idata['ticker'] = ($data[$fields['ticker']] == "#N/A N/A" || !isset($data[$fields['ticker']])) ? '' : $data[$fields['ticker']];

						if(!empty($idata['ticker']))
						{
							$arr_tickers = \DB::table('tickers')
                                    ->where('ticker_name', $idata['ticker'])
                                    ->where('country_id', $idata['country_id'])
                                    ->where('market_id', $idata['market_id'])
                                    ->first();
			                if($arr_tickers){
			                    $ticker_id = $arr_tickers->id;
			                } else {
			                    $TK = new \App\Models\Tickers();
			                    $TK->ticker_name = $idata['ticker'];
			                    $TK->country_id = $idata['country_id'];
			                    $TK->market_id = $idata['market_id'];
			                    $TK->ticker_type = 1;
			                    $TK->save();
			                    $ticker_id = $TK->id;
			                }
							$idata['ticker_id'] = $ticker_id;
						}

						$idata['benchmark'] = ($data[$fields['benchmark']] == "#N/A N/A" || !isset($data[$fields['benchmark']]) || empty($data[$fields['benchmark']])) ? 0 : 1;
						$idata['benchmark_family'] = ($data[$fields['benchmark']] == "#N/A N/A" || !isset($data[$fields['benchmark']])) ? '' : $data[$fields['benchmark']];
						$idata['cpn'] = ($data[$fields['cpn']] == "#N/A N/A" || !isset($data[$fields['cpn']])) ? '' : $data[$fields['cpn']];
						$idata['security_name'] = ($data[$fields['security_name']] == "#N/A N/A" || !isset($data[$fields['security_name']])) ? '' : $data[$fields['security_name']];
						
						$idata['maturity_date'] =  '0000-00-00';
						if(isset($data[$fields['maturity']]) && !empty($data[$fields['maturity']])){
							$arr_date = explode("/", $data[$fields['maturity']]);
							if($arr_date[0] < 10){
								$month = '0'.$arr_date[0];
							} else {
								$month = $arr_date[0];
							}
							if($arr_date[1] < 10){
								$date = '0'.$arr_date[1];
							} else {
								$date = $arr_date[1];
							}
							$full_date = $arr_date[2]."-".$month."-".$date;
							$idata['maturity_date'] = $full_date;
						}

						if(!isset($idata['CUSIP']) || empty($idata['CUSIP']))
						{
							continue;
						}

						\DB::enableQueryLog();

						if (!empty($idata) && is_array($idata))
						{

							$security = Securities::where("CUSIP",$idata['CUSIP'])
							            ->where("market_id",$idata['market_id'])->first();
							            // ->where("created","<=", $created_date)->first();
							if($security){
								$security_id = $security->id;
								//update if date less then "created","<=", $created_date
								if($security->created <= $created_date){
									$security_data = Securities::where('id', $security->id)
														   ->update($idata);
								}
							} else {
								// new security create here
								$security_el = new Securities;
								$create_security = $security_el->create($idata);
								$security_id = $create_security->id;
							}
							$hdata['security_id'] 	= $security_id;
							if ($security->market_id == 5) {
								$hdata['DUR_ADJ_MID'] 	= $idata['dur_adj_mid'];
								$hdata['YLD_YTM_MID'] 	= $idata['yld_ytm_mid'];
								$hdata['Z_SPRD_MID'] 	= $idata['z_sprd_mid'];
								$Bond_historical_data = BondHistoricalData::where("security_id", $security_id)
																->where("created", $created_date)->first();
								if(!empty($Bond_historical_data)){
									BondHistoricalData::where('id', $Bond_historical_data->id)
															   ->update($hdata);
								} else{
									$bond_histotry_el = new BondHistoricalData;
									$bond_histotry_el->create($hdata);
								}
							} else {
								$Historical_data = HistoricalData::where("security_id", $security_id)
																->where("created", $created_date)->first();
								if(!empty($Historical_data)){
									HistoricalData::where('id', $Historical_data->id)
															   ->update($hdata);
								} else{
									$histotry_el = new HistoricalData;
									$histotry_el->create($hdata);
								}
							}
						}
					}
					$bytes = ftell($file);
					$i++;
				}
				fclose($file);
				return ['status'=> 1, 'msg'=> 'Your data was inserted'];
			}
		}
		return ['status'=>$status, 'msg'=>$msg];
    }

	public function country()
	{
		$countries = \DB::table('countries')->pluck('id','country_code')->toArray();
		$key_countries = array_keys($countries);
		$securities = Securities::all();
		foreach ($securities as $security) {
			$country = $security->country;
			if (in_array($country , $key_countries)) {
				$security->country_id = $countries[$country];
				$security->save();
			}
		}
		return "Country Id added to the existing securities without country";
	}
	public function edit_security_data($id)
	{
		$checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$EDIT_SECURITY);

        if($checkrights)
        {
            return $checkrights;
        }
		$data = array();
		$obj = Securities::find($id);
		if(!$obj){
			return abort(404);
		}
        $data['formObj'] = $obj;
        $data['page_title'] = "Edit Security Detail";
        $data['buttonText'] = "Update";
        $data['action_url'] = "update-security-data";
        $data['action_params'] = $obj->id;
        $data['method'] = "PUT";
        $data['list_url'] = url('admin/listsecurity');
		$data['countries'] = \App\Models\Country::pluck('title','id')->all();
		$data['markets'] = \App\Models\MarketType::pluck('market_name','id')->all();
		$data['benchmark_family_list'] = Securities::where('benchmark_family', "!=", "")
												   ->groupBy('benchmark_family')
												   ->pluck("benchmark_family","benchmark_family")->all();
		return view('admin.uploadExcel.edit',$data);

	}
	public function update_security_data(Request $request, $id)
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
            'market_id' => 'required|exists:market_type,id',
            'CUSIP' => 'required|min:2',
            'ticker' => 'required',
            'cpn' => 'required',
            'security_name' => 'required',
            'maturity_date' => 'required',
            'dur_adj_mid' => 'required',
            'bid_price' => 'required|numeric|min:0',
            'ask_price' => 'required|numeric|min:0',
            'last_price' => 'required|numeric|min:0',
            'low_price' => 'required|numeric|min:0',
            'high_price' => 'required|numeric|min:0',
            'yld_ytm_mid' => 'required',
            'z_sprd_mid' => 'required',
            'net_change' => 'required',
            'percentage_change' => 'required',
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
			
			if(empty($new_benchmark) && empty($select_benchmark)){
				$status = 0;
				$msg = 'please enter at least one benchmark!';
				return ['status' => $status, 'msg'=>$msg];
			}
			elseif (!empty($new_benchmark) && !empty($select_benchmark)) {
				$status = 0;
				$msg = 'Please enter only one benchmark';
				return ['status' => $status, 'msg'=>$msg];
			}
			else {
				$input = $request->all();
	            $model->update($input);

				if (isset($new_benchmark) && !empty($new_benchmark)) {
					$model->benchmark_family = $new_benchmark;
					$model->save();
				}
				elseif (isset($select_benchmark) && !empty($select_benchmark)) {
					$model->benchmark_family = $select_benchmark;
					$model->save();
				}
			}
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

	// public function massinsert(Request $request)
	// {
	// 	$checkrights = \App\Models\Admin::checkPermission(\App\Models\Admin::$EXCEL_IMPORT_GRAPH);
	//
	// 	if($checkrights)
	// 	{
	// 		return $checkrights;
	// 	}
	//
	// 	$status = 1;
	// 	$msg = "Your data was successfully added";
	// 	$data = [];
	//
	// 	$validator = Validator::make($request->all(), [
	// 		'excelToUpload' => 'required|excel',
	// 	]);
	//
	// 	if ($validator->fails())
	// 	{
	// 		$messages = $validator->messages();
	//
	// 		$status = 0;
	// 		$msg = "";
	//
	// 		foreach ($messages->all() as $message)
	// 		{
	// 			$msg .= $message . "<br />";
	// 		}
	// 	}
	// 	else
	// 	{
	// 		if ($request->hasFile('excelToUpload'))
	// 		{
	// 			$csv_file = $request->file('excelToUpload');
	// 			$size = $_FILES['excelToUpload']['size'];
	// 			$ext = $csv_file->getClientOriginalExtension();
	// 			$markets = MarketType::pluck('id','market_name');
	// 			$filename = time().".".$ext;
	// 			$uploadPath = 'uploads' . DIRECTORY_SEPARATOR . 'csv_files'.DIRECTORY_SEPARATOR;
	// 			$csv_file->move($uploadPath, $filename);
	// 			$file = fopen($uploadPath.$filename,"r");
	// 			$i = 0;
	// 			$bytes = ftell($file);
	// 			$fields = [];
	//
	// 			while(! feof($file))
	// 			{
	// 				$idata = [];
	// 				$data =  fgetcsv($file);
	// 				if (empty($data)){
	// 					break;
	// 				}
	// 				if ($i == 0)
	// 				{
	// 					foreach ($data as $key => $value)
	// 					{
	// 						$fields[strtolower(trim($value))] = $key;
	// 					}
	// 				}
	// 				else
	// 				{
	// 					// Array for historical data
	// 					$hdata = [];
	//
	// 					$idata['created'] = \DB::raw('CURDATE()');
	// 					$idata['bid_price'] = ($data[$fields['px_bid']] == "#N/A N/A" || !isset($data[$fields['px_bid']])) ? '' : str_replace(',','',$data[$fields['px_bid']]);
	// 					$idata['ask_price'] = ($data[$fields['px_ask']] == "#N/A N/A" || !isset($data[$fields['px_ask']])) ? '' : str_replace(',','',$data[$fields['px_ask']]);
	// 					$idata['last_price'] = ($data[$fields['px_last']] == "#N/A N/A" || !isset($data[$fields['px_last']])) ? '' : str_replace(',','',$data[$fields['px_last']]);
	// 					$idata['low_price'] = ($data[$fields['px_low']] == "#N/A N/A" || !isset($data[$fields['px_low']])) ? '' : str_replace(',','',$data[$fields['px_low']]);
	// 					$idata['high_price'] = ($data[$fields['px_high']] == "#N/A N/A" || !isset($data[$fields['px_high']])) ? '' : str_replace(',','',$data[$fields['px_high']]);
	// 					$idata['net_change'] = ($data[$fields['chg_net_1d']] == "#N/A N/A" || !isset($data[$fields['chg_net_1d']])) ? '' : str_replace(',','',$data[$fields['chg_net_1d']]);
	// 					$idata['percentage_change'] = ($data[$fields['chg_pct_1d']] == "#N/A N/A" || !isset($data[$fields['chg_pct_1d']])) ? '' : str_replace(',','',$data[$fields['chg_pct_1d']]);
	//
	// 					// Only historical_data table's colums will be added to this array.
	// 					$hdata = $idata;
	//
	// 					$idata['yld_ytm_mid'] = ($data[$fields['yld_ytm_mid']] == "#N/A N/A" || !isset($data[$fields['yld_ytm_mid']])) ? '' : $data[$fields['yld_ytm_mid']];
	// 					$idata['z_sprd_mid'] = ($data[$fields['z_sprd_mid']] == "#N/A N/A" || !isset($data[$fields['z_sprd_mid']])) ? '' : $data[$fields['z_sprd_mid']];
	// 					$idata['dur_adj_mid'] = ($data[$fields['dur_adj_mid']] == "#N/A N/A" || !isset($data[$fields['dur_adj_mid']])) ? '' : $data[$fields['dur_adj_mid']];
	// 					$idata['CUSIP'] = ($data[$fields['cusip']] == "#N/A N/A" || !isset($data[$fields['cusip']])) ? "" : $data[$fields['cusip']] ;
	// 					$idata['market_id'] = ($data[$fields['market']] == "#N/A N/A" || !isset($data[$fields['market']])) ? '' : $markets[$data[$fields['market']]];
	// 					$idata['country'] = ($data[$fields['country']] == "#N/A N/A" || !isset($data[$fields['country']])) ? '' : $data[$fields['country']];
	// 					$idata['ticker'] = ($data[$fields['ticker']] == "#N/A N/A" || !isset($data[$fields['ticker']])) ? '' : $data[$fields['ticker']];
	// 					$idata['benchmark'] = ($data[$fields['benchmark']] == "#N/A N/A" || !isset($data[$fields['benchmark']])) ? '' : $data[$fields['benchmark']];
	// 					$idata['cpn'] = ($data[$fields['cpn']] == "#N/A N/A" || !isset($data[$fields['cpn']])) ? '' : $data[$fields['cpn']];
	// 					$idata['security_name'] = ($data[$fields['security_name']] == "#N/A N/A" || !isset($data[$fields['security_name']])) ? '' : $data[$fields['security_name']];
	// 					$idata['maturity_date'] = ($data[$fields['maturity']] == "#N/A N/A" || !isset($data[$fields['maturity']])) ? '' : date('Y-m-d', strtotime($data[$fields['maturity']]));
	// 					echo "<pre>";
	// 					print_r($fields);
	// 					print_r($idata);
	// 					exit;
	// 					// Update if any record exists Or Create a new Security
	// 					if (!empty($idata) && is_array($idata))
	// 					{
	//
	// 						$security = Securities::updateOrCreate(
	// 										[
	// 											'CUSIP' => $idata['CUSIP'],
	// 											'market_id' => $idata['market_id']
	// 										],
	// 										$idata
	// 									);
	//
	// 						$hdata['security_id'] = $security->id;
	// 						$hdata['created'] = \DB::raw('CURDATE()');
	// 						if ($security->market_id == 5)
	// 						{
	// 							$hdata['DUR_ADJ_MID'] = $idata['dur_adj_mid'];
	// 							$hdata['YLD_YTM_MID'] = $idata['yld_ytm_mid'];
	// 							$hdata['Z_SPRD_MID'] = $idata['z_sprd_mid'];
	// 							\DB::table('bond_historical_data')->insert($hdata);
	// 						}
	// 						else
	// 						{
	// 							\DB::table('historical_data')->insert($hdata);
	// 						}
	// 					}
	//
	//
	//
	// 				}
	// 				$i++;
	// 			}
	//
	//
	// 			fclose($file);
	// 			return ['status'=> 1, 'msg'=> 'Your data was inserted'];
	// 		}
	// 	}
	// 	return ['status'=>$status, 'msg'=>$msg];
	// }
}
