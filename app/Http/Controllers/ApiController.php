<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use \PDO;
use Validator;

class ApiController extends Controller
{
    public function __construct()
    {
        
    }


    public function SelectMarkets(Request $request)
    {
        $data = callCustomSP('SELECT id, `market_name` FROM market_type ORDER BY id');
        $r_data = json_encode($data);
        return ['status' => 1,'msg' => "OK", "data" => $r_data];
    }

    public function TopMarketData(Request $request)
    {
        $data = callCustomSP('CALL select_market()');
        $r_data = json_encode($data);
        return ['status' => 1,'msg' => "OK", "data" => $r_data];
    }

    public function TopGainer(Request $request, $market_id = null)
    {
        $market_data  = "CALL select_Top_Gainer(".$market_id.")";
        $gainer_data = callCustomSP($market_data);
        $returnData['top_gainer'] = $gainer_data;
        
        if(isset($gainer_data[0])){
            $security_id = $gainer_data[0]['id']; $month_id = 1; $benchmark = '';
            $market_data  = "CALL Select_Historical_Data(".$security_id.", ".$month_id.")";
            $History_data = callCustomSP($market_data);
            $returnData['gainer_history_data'] = $History_data;

            $security_id = $gainer_data[0]['id'];
            $banchmark_data  = "CALL Select_banchmark(".$security_id.")";
            $banchmark_data_arr = callCustomSP($banchmark_data);
            $returnData['arr_banchmark'] = $banchmark_data_arr;
        }

        $market_data  = "CALL select_Top_Loser(".$market_id.")";
        $loser_data = callCustomSP($market_data);
        $returnData['top_loser'] = $loser_data;
        if(isset($loser_data[0])){
            $security_id = $loser_data[0]['id']; $month_id = 3; $benchmark = '';
            $market_data  = "CALL Select_Historical_Data(".$security_id.", ".$month_id.")";
            $History_data = callCustomSP($market_data);
            $returnData['loser_history_data'] = $History_data;
        }

        return ['status' => 1,'msg' => "OK", "data" => $returnData];
    }

    public function HistoryChart(Request $request)
    {
        $returnData     = array();
        $security_id    = $request->get("security_id");
        $month_id       =  $request->get("month_id", 1);
        $benchmark_id       =  $request->get("month_id", 0);
        $price_id = $request->get("price_id");
        $market_id = $request->get("market_id");

        $history_data   = "CALL Select_Historical_Data(".$security_id.", ".$month_id.")";
        $data           = callCustomSP($history_data);
        $returnData['history_data'] = $data;

        $returnData['benchmark_history_data'] = [];

        if($benchmark_id > 0)
        {
            $history_data   = "CALL Select_Historical_Data(".$benchmark_id.", ".$month_id.")";
            $data           = callCustomSP($history_data);
            $benchmark_history_data = $data;            

            if(!empty($benchmark_history_data))
            {
                $dataKeys = [];

                foreach($returnData['history_data'] as $row)
                {
                    if($price_id != 1 && $market_id == 5)
                    {
                        if($price_id == 2)
                        {
                            $dataKeys[$row['created']]['column1'] = $row['YLD_YTM_MID'];
                        }   
                        else if($price_id == 3)
                        {
                            $dataKeys[$row['created']]['column1'] = $row['Z_SPRD_MID'];
                        } 
                    }   
                    else
                    {
                        $dataKeys[$row['created']]['column1'] = $row['last_price'];
                    }   

                    $dataKeys[$row['created']]['column2'] = 0;
                    $dataKeys[$row['created']]['date'] = $row['created_format'];
                }

                foreach($benchmark_history_data as $row)
                {
                    if($price_id != 1 && $market_id == 5)
                    {
                        if($price_id == 2)
                        {
                            $dataKeys[$row['created']]['column2'] = $row['YLD_YTM_MID'];
                        }   
                        else if($price_id == 3)
                        {
                            $dataKeys[$row['created']]['column2'] = $row['Z_SPRD_MID'];
                        } 
                    }   
                    else
                    {
                        $dataKeys[$row['created']]['column2'] = $row['last_price'];
                    }   

                    if(!isset($dataKeys[$row['created']]['column1']))
                    $dataKeys[$row['created']]['column1'] = 0;

                    $dataKeys[$row['created']]['date'] = $row['created_format'];                                                             
                }

                ksort($dataKeys);

                $i = 1;
                $finalData[0] = ["Date","Column 1","Column 2"];
                foreach($dataKeys as $key => $val)
                {
                    if(!empty($dataKeys[$key]['date']))
                    {
                        $finalData[$i] = [$dataKeys[$key]['date'], $dataKeys[$key]['column1'], $dataKeys[$key]['column2']];

                        $i++;                        
                    }    
                }

                if(empty($dataKeys))
                {
                    $finalData[0] = ["Date",0,0];
                }

                // echo "<pre>";
                // print_r($finalData);
                // exit;

                $returnData['benchmark_history_data'] = $finalData;
            }
        }    

        $banchmark_data     = "CALL Select_banchmark(".$security_id.")";
        $banchmark_data_arr = callCustomSP($banchmark_data);
        $returnData['arr_banchmark'] = $banchmark_data_arr;

        return ['status' => 1,'msg' => "OK", "data" => $returnData];
    }
}
