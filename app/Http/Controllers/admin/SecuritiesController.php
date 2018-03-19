<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Market_Type;
use App\Models\Securities;
use Validator;

class SecuritiesController extends Controller
{
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

      return view('admin.UploadExcel.upload', $data);
    }

    public function validateexcel(Request $request)
    {
      // echo "<pre>";
      // print_r($_FILES);
      // exit();
      // dd($request);
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
          $path = $request->file('excelToUpload')->getRealPath();
          $data = \Excel::load($path)->get()->toArray();
          $lastId = \DB::table('securities')->select('id')->orderBy('id', 'DESC')->first();
          $lastId = isset($lastId->id) ? $lastId->id : 0;
          $arr = [];
          $arr1 = [];
          $excp = [];

          $hdata = [];
          $bhdata = [];
          $markets = Market_Type::pluck('id','market_name');
          $key2 = 0;
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
              $arr[$key2]['CUSIP'] = $value['cusip'];
              $arr[$key2]['market_id'] = $value['market'];
              $arr[$key2]['country'] = $value['country'];
              $arr[$key2]['ticker'] = $value['ticker'];
              $arr[$key2]['benchmark'] = $value['benchmark'];
              $arr[$key2]['cpn'] = $value['cpn'];
              $arr[$key2]['security_name'] = $value['security_name'];
              $arr[$key2]['maturity_date'] = $value['maturity'];
              $arr[$key2]['dur_adj_mid'] = $value['dur_adj_mid'];
              $arr[$key2]['bid_price'] = $value['px_bid'];
              $arr[$key2]['ask_price'] = $value['px_ask'];
              $arr[$key2]['last_price'] = $value['px_last'];
              $arr[$key2]['low_price'] = $value['px_low'];
              $arr[$key2]['high_price'] = $value['px_high'];
              $arr[$key2]['yld_ytm_mid'] = $value['yld_ytm_mid'];
              $arr[$key2]['z_sprd_mid'] = $value['z_sprd_mid'];
              $arr[$key2]['net_change'] = $value['chg_net_1d'];
              $arr[$key2]['percentage_change'] = $value['chg_pct_1d'];
              $arr[$key2]['created'] = \DB::raw(' CURDATE() ');
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
}
