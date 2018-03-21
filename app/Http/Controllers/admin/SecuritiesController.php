<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MarketType;
use App\Models\Securities;
use Excel;
use Validator;

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
        $data['page_title'] = "Upload Data Excel";
        $data['validate_url'] = url('admin/validate');
        $data['buttonText '] = "Upload Excel/CSV";

        return view('admin.uploadExcel.upload', $data);
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

		$validator = Validator::make($request->all(), [
			'excelToUpload' => 'required|excel',
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
			if ($request->hasFile('excelToUpload'))
			{
				$csv_file = $request->file('excelToUpload');
				$size = $_FILES['excelToUpload']['size'];
				$ext = $csv_file->getClientOriginalExtension();
				$markets = MarketType::pluck('id','market_name');
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
					if ($i == 0)
					{
						foreach ($data as $key => $value)
						{
							$fields[strtolower(trim($value))] = $key;
						}
					}
					else
					{
						// Array for historical data
						$hdata = [];

						$idata['created'] = \DB::raw('CURDATE()');
						$idata['bid_price'] = ($data[$fields['px_bid']] == "#N/A N/A" || !isset($data[$fields['px_bid']])) ? '' : str_replace(',','',$data[$fields['px_bid']]);
						$idata['ask_price'] = ($data[$fields['px_ask']] == "#N/A N/A" || !isset($data[$fields['px_ask']])) ? '' : str_replace(',','',$data[$fields['px_ask']]);
						$idata['last_price'] = ($data[$fields['px_last']] == "#N/A N/A" || !isset($data[$fields['px_last']])) ? '' : str_replace(',','',$data[$fields['px_last']]);
						$idata['low_price'] = ($data[$fields['px_low']] == "#N/A N/A" || !isset($data[$fields['px_low']])) ? '' : str_replace(',','',$data[$fields['px_low']]);
						$idata['high_price'] = ($data[$fields['px_high']] == "#N/A N/A" || !isset($data[$fields['px_high']])) ? '' : str_replace(',','',$data[$fields['px_high']]);
						$idata['net_change'] = ($data[$fields['chg_net_1d']] == "#N/A N/A" || !isset($data[$fields['chg_net_1d']])) ? '' : str_replace(',','',$data[$fields['chg_net_1d']]);
						$idata['percentage_change'] = ($data[$fields['chg_pct_1d']] == "#N/A N/A" || !isset($data[$fields['chg_pct_1d']])) ? '' : str_replace(',','',$data[$fields['chg_pct_1d']]);

						// Only historical_data table's colums will be added to this array.
						$hdata = $idata;

						$idata['yld_ytm_mid'] = ($data[$fields['yld_ytm_mid']] == "#N/A N/A" || !isset($data[$fields['yld_ytm_mid']])) ? '' : $data[$fields['yld_ytm_mid']];
						$idata['z_sprd_mid'] = ($data[$fields['z_sprd_mid']] == "#N/A N/A" || !isset($data[$fields['z_sprd_mid']])) ? '' : $data[$fields['z_sprd_mid']];
						$idata['dur_adj_mid'] = ($data[$fields['dur_adj_mid']] == "#N/A N/A" || !isset($data[$fields['dur_adj_mid']])) ? '' : $data[$fields['dur_adj_mid']];
						$idata['CUSIP'] = ($data[$fields['cusip']] == "#N/A N/A" || !isset($data[$fields['cusip']])) ? "" : $data[$fields['cusip']] ;
						$idata['market_id'] = ($data[$fields['market']] == "#N/A N/A" || !isset($data[$fields['market']])) ? '' : $markets[$data[$fields['market']]];
						$idata['country'] = ($data[$fields['country']] == "#N/A N/A" || !isset($data[$fields['country']])) ? '' : $data[$fields['country']];
						$idata['ticker'] = ($data[$fields['ticker']] == "#N/A N/A" || !isset($data[$fields['ticker']])) ? '' : $data[$fields['ticker']];
						$idata['benchmark'] = ($data[$fields['benchmark']] == "#N/A N/A" || !isset($data[$fields['benchmark']]) || empty($data[$fields['benchmark']])) ? 0 : 1;
						$idata['benchmark_family'] = ($data[$fields['benchmark']] == "#N/A N/A" || !isset($data[$fields['benchmark']])) ? '' : $data[$fields['benchmark']];
						$idata['cpn'] = ($data[$fields['cpn']] == "#N/A N/A" || !isset($data[$fields['cpn']])) ? '' : $data[$fields['cpn']];
						$idata['security_name'] = ($data[$fields['security_name']] == "#N/A N/A" || !isset($data[$fields['security_name']])) ? '' : $data[$fields['security_name']];
						$idata['maturity_date'] = ($data[$fields['maturity']] == "#N/A N/A" || !isset($data[$fields['maturity']]) || empty($data[$fields['maturity']])) ? '0000-00-00' : date('Y-m-d', strtotime(str_replace("-","/",$data[$fields['maturity']])));
						// Update if any record exists Or Create a new Security
						if (!empty($idata) && is_array($idata))
						{

							$security = Securities::updateOrCreate(
											[
												'CUSIP' => $idata['CUSIP'],
												'market_id' => $idata['market_id']
											],
											$idata
										);

							$hdata['security_id'] = $security->id;
							$hdata['created'] = \DB::raw('CURDATE()');
							if ($security->market_id == 5)
							{
								$hdata['DUR_ADJ_MID'] = $idata['dur_adj_mid'];
								$hdata['YLD_YTM_MID'] = $idata['yld_ytm_mid'];
								$hdata['Z_SPRD_MID'] = $idata['z_sprd_mid'];
								\DB::table('bond_historical_data')->insert($hdata);
							}
							else
							{
								\DB::table('historical_data')->insert($hdata);
							}
						}



					}
					$i++;
				}


				fclose($file);
				return ['status'=> 1, 'msg'=> 'Your data was inserted'];
			}
		}
		return ['status'=>$status, 'msg'=>$msg];
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
