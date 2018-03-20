<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Market_Type;
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
			$lastId = \DB::table('securities')->select('id')->orderBy('id', 'DESC')->first();
			$lastId = isset($lastId->id) ? $lastId->id : 0;
			$arr = [];
			$arr1 = [];
			$excp = [];

			$hdata = [];
			$bhdata = [];
			$markets = Market_Type::pluck('id','market_name');
			$key2 = 0;



			$path = $request->file('excelToUpload')->getRealPath();
			$data = \Excel::load($path)->get()->toArray();
			dd("COOL");
        	foreach ($data as $key => $value)
        	{
	            $value['market'] = $markets[$value['market']];
	            $sdata = \DB::table('securities')->where('market_id', $value['market'])->where('CUSIP', $value['cusip'])->get();
	            if ($sdata->count() >0)
	            {
	              $arr1['country'] = isset($value['country']) ? $value['country'] : '' ;
	              $arr1['ticker'] = isset($value['ticker']) ? $value['ticker'] : '' ;
	              $arr1['benchmark'] = isset($value['benchmark']) ? $value['benchmark'] : '' ;
	              $arr1['cpn'] = isset($value['cpn']) ? $value['cpn'] : '' ;
	              $arr1['security_name'] = isset($value['security_name']) ? $value['security_name'] : '' ;
	              $arr1['maturity_date'] = isset($value['maturity']) ? $value['maturity'] : '' ;
	              $arr1['dur_adj_mid'] = isset($value['dur_adj_mid']) ? $value['dur_adj_mid'] : '' ;
	              $arr1['bid_price'] = isset($value['px_bid']) ? $value['px_bid'] : '' ;
	              $arr1['ask_price'] = isset($value['px_ask']) ? $value['px_ask'] : '' ;
	              $arr1['last_price'] = isset($value['px_last']) ? $value['px_last'] : '' ;
	              $arr1['low_price'] = isset($value['px_low']) ? $value['px_low'] : '' ;
	              $arr1['high_price'] = isset($value['px_high']) ? $value['px_high'] : '' ;
	              $arr1['yld_ytm_mid'] = isset($value['yld_ytm_mid']) ? $value['yld_ytm_mid'] : '' ;
	              $arr1['z_sprd_mid'] = isset($value['z_sprd_mid']) ? $value['z_sprd_mid'] : '' ;
	              $arr1['net_change'] = isset($value['chg_net_1d']) ? $value['chg_net_1d'] : '' ;
	              $arr1['percentage_change'] = isset($value['chg_pct_1d']) ? $value['chg_pct_1d'] : '';
	              foreach ($sdata as $key => $value)
	              {
	                $query = \DB::table('securities')->where('id', $value->id)->update($arr1);
	              }

	            }
	            else
	             {
	              $arr[$key2]['CUSIP'] = isset($value['cusip']) ? $value['cusip'] : '' ;
	              $arr[$key2]['market_id'] = isset($value['market']) ? $value['market'] : '';
	              $arr[$key2]['country'] = isset($value['country']) ? $value['country'] : '';
	              $arr[$key2]['ticker'] = isset($value['ticker']) ? $value['ticker'] : '';
	              $arr[$key2]['benchmark'] = isset($value['benchmark']) ? $value['benchmark'] : '';
	              $arr[$key2]['cpn'] = isset($value['cpn']) ? $value['cpn'] : '';
	              $arr[$key2]['security_name'] = isset($value['security_name']) ? $value['security_name'] : '' ;
	              $arr[$key2]['maturity_date'] = isset($value['maturity']) ? $value['maturity'] : '';
	              $arr[$key2]['dur_adj_mid'] = isset($value['dur_adj_mid']) ? $value['dur_adj_mid'] : '';
	              $arr[$key2]['bid_price'] = isset($value['px_bid']) ? $value['px_bid'] : '';
	              $arr[$key2]['ask_price'] = isset($value['px_ask']) ? $value['px_ask'] : '';
	              $arr[$key2]['last_price'] = isset($value['px_last']) ? $value['px_last'] : '';
	              $arr[$key2]['low_price'] = isset($value['px_low']) ? $value['px_low'] : '';
	              $arr[$key2]['high_price'] = isset($value['px_high']) ? $value['px_high'] : '';
	              $arr[$key2]['yld_ytm_mid'] = isset($value['yld_ytm_mid']) ? $value['yld_ytm_mid'] : '';
	              $arr[$key2]['z_sprd_mid'] = isset($value['z_sprd_mid']) ? $value['z_sprd_mid'] : '';
	              $arr[$key2]['net_change'] = isset($value['chg_net_1d']) ? $value['chg_net_1d'] :'';
	              $arr[$key2]['percentage_change'] = isset($value['chg_pct_1d']) ? $value['chg_pct_1d'] : '';
	              $arr[$key2]['created'] = isset($value['date']) ? $value['date'] : '' ;
	              $key2 = $key2 + 1;
	            }
          	}
          	\DB::table('securities')->insert($arr);


			$sdata = \DB::table('securities')->where('id', '>', $lastId)->get();
			$key3= 0; $key4 = 0;
			foreach ($sdata as $key => $value)
			{
				if ($value->market_id == 5)
				{
					$bhdata[$key3]['security_id'] = $value->id;
					$bhdata[$key3]['DUR_ADJ_MID'] = $value->dur_adj_mid;
					$bhdata[$key3]['bid_price'] = $value->bid_price;
					$bhdata[$key3]['ask_price'] = $value->ask_price;
					$bhdata[$key3]['last_price'] = $value->last_price;
					$bhdata[$key3]['low_price'] = $value->low_price;
					$bhdata[$key3]['high_price'] = $value->high_price;
					$bhdata[$key3]['YLD_YTM_MID'] = $value->yld_ytm_mid;
					$bhdata[$key3]['Z_SPRD_MID'] = $value->z_sprd_mid;
					$bhdata[$key3]['net_change'] = $value->net_change;
					$bhdata[$key3]['percentage_change'] = $value->percentage_change;
					$bhdata[$key3]['created'] = \DB::raw('CURDATE()');
					$key3 = $key3 + 1;
				}
				else
				{
					$hdata[$key4]['security_id'] = $value->id;
					$hdata[$key4]['bid_price'] = $value->bid_price;
					$hdata[$key4]['ask_price'] = $value->ask_price;
					$hdata[$key4]['last_price'] = $value->last_price;
					$hdata[$key4]['low_price'] = $value->low_price;
					$hdata[$key4]['high_price'] = $value->high_price;
					$hdata[$key4]['net_change'] = $value->net_change;
					$hdata[$key4]['percentage_change'] = $value->percentage_change;
					$hdata[$key4]['created'] = \DB::raw('CURDATE()');
					$key4 = $key4 + 1;
				}
			}

	    	\DB::table('historical_data')->insert($hdata);
	    	\DB::table('bond_historical_data')->insert($bhdata);
	    }
        else
        {
        	$status = 0;
        	$msg = "No file to upload";
        	return ['status'=>$status, 'msg'=>$msg];
        }
      }
      return ['status'=>$status, 'msg'=>$msg];
    }

	public function massinsert(Request $request)
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
				$filename = time().".".$ext;
				$uploadPath = 'uploads' . DIRECTORY_SEPARATOR . 'csv_files'.DIRECTORY_SEPARATOR;
				$csv_file->move($uploadPath, $filename);
				$file = fopen($uploadPath.$filename,"r");
				$i = 0;
				$bytes = ftell($file);

				for ($j=0; $j < 420000 ; $j = $j + 5000)
				{
					$idata = [];
					if ($bytes >= $size) {
						break;
					}
					fseek($file, $bytes);
					while(! feof($file))
					{
						if ($i > $j)
						{
							break;
						}
						$data =  fgetcsv($file);
						if (empty($data))
							break;
						if ($i != 0)
						{
							$l = $i -1;
							$idata[$l]['created'] = date('Y-m-d', strtotime($data[0]));
							$idata[$l]['CUSIP'] = $data[1];
							$idata[$l]['dur_adj_mid'] = ($data[2] == "#N/A N/A") ? '' : $data[2];
							$idata[$l]['bid_price'] = ($data[3] == "#N/A N/A") ? '' : $data[3];
							$idata[$l]['ask_price'] = ($data[4] == "#N/A N/A") ? '' : $data[4];
							$idata[$l]['last_price'] = ($data[5] == "#N/A N/A") ? '' : $data[5];
							$idata[$l]['low_price'] = ($data[6] == "#N/A N/A") ? '' : $data[6];
							$idata[$l]['high_price'] = ($data[7] == "#N/A N/A") ? '' : $data[7];
							$idata[$l]['yld_ytm_mid'] = ($data[8] == "#N/A N/A") ? '' : $data[8];
							$idata[$l]['z_sprd_mid'] = ($data[9] == "#N/A N/A") ? '' : $data[9];
							$idata[$l]['net_change'] = ($data[10] == "#N/A N/A") ? '' : $data[10];
							$idata[$l]['percentage_change'] = ($data[11] == "#N/A N/A") ? '' : $data[11];
						}
						$i++;
					}


					if (!empty($idata))
					{
						\DB::table('historical_data')->insert($idata);
					}
					$bytes = ftell($file);
				}

				fclose($file);
				return ['status'=> 1, 'msg'=> 'Your data was inserted'];
			}
		}
		return ['status'=>$status, 'msg'=>$msg];
	}
}
