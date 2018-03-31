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
        $market_data  = "CALL select_Top_Gainer(".$market_id.",0)";
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

        $market_data  = "CALL select_Top_Loser(".$market_id.",0)";
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
        $benchmark_id       =  $request->get("benchmark_id", 0);
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

                // echo "<pre>";
                // print_r($returnData['history_data']);
                // print_r($benchmark_history_data);
                // exit;

                ksort($dataKeys);

                // print_r($dataKeys);

                $i = 0;

                // $finalData[0] = ["Date","Column 1","Column 2"];

                foreach($dataKeys as $key => $val)
                {
                    if(!empty($dataKeys[$key]['date']))
                    {
                        $finalData[$i] = [$dataKeys[$key]['date'], $dataKeys[$key]['column1'], $dataKeys[$key]['column2']];

                        $i++;                        
                    }    
                }

                // if(empty($dataKeys))
                // {
                //     $finalData[0] = ["Date",0,0];
                // }

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
    
    public function getEconomicsHistoryChart(Request $request, $country)
    {
        $market_id = $request->get("market_id");
        $benchmark_id = $request->get("benchmark_id");
        $month_id = $request->get("month_id");
        $price_id = $request->get("price_id");
        
        $status = 1;
        $msg = "OK";        
        
        $history_data   = "CALL select_economic_bond_historical_data(".$country.", ".$month_id.")";
        $data = [];
        $data['history_data']  = callCustomSP($history_data);

        $data['benchmark_history_data'] = [];

        if($benchmark_id > 0)
        {
            $history_data   = "CALL Select_Historical_Data(".$benchmark_id.", ".$month_id.")";
            $dataTemp           = callCustomSP($history_data);
            $benchmark_history_data = $dataTemp;            

            if(true)
            {
                $dataKeys = [];
                $i = 0;
                foreach($data['history_data'] as $row)
                {
                    $dataKeys[$i]['title1'] = $row['title'];    
                    
                    if($price_id != 1 && $market_id == 5)
                    {
                        if($price_id == 2)
                        {
                            $dataKeys[$i]['price1'] = $row['YLD_YTM_MID'];
                        }   
                        else if($price_id == 3)
                        {
                            $dataKeys[$i]['price1'] = $row['Z_SPRD_MID'];
                        } 
                    }   
                    else
                    {
                        $dataKeys[$i]['price1'] = $row['last_price'];
                    }                       
                    
                    $dataKeys[$i]['title2'] = "";
                    $dataKeys[$i]['price2'] = 0;                                        
                    
                    $i++;
                }

                $i = 0;    
                
                foreach($benchmark_history_data as $row)
                {
                    $dataKeys[$i]['title2'] = $row['title'];
                    
                    if($price_id != 1 && $market_id == 5)
                    {
                        if($price_id == 2)
                        {
                            $dataKeys[$i]['price2'] = $row['YLD_YTM_MID'];
                        }   
                        else if($price_id == 3)
                        {
                            $dataKeys[$i]['price2'] = $row['Z_SPRD_MID'];
                        } 
                    }   
                    else
                    {
                        $dataKeys[$i]['price2'] = $row['last_price'];
                    }                                           
                    
                    if(!isset($dataKeys[$i]['title1']))
                    {
                        $dataKeys[$i]['title1'] = "";
                        $dataKeys[$i]['price1'] = 0;
                    }
                }
                $data['benchmark_history_data'] = $dataKeys;
            }
        }
        
        return ["status" => $status, "msg" => $msg, "data" => $data];
    }

    public function getLastUploadDate(Request $request)
    {
        $path_file_name = 'uploads/last-updated-date.json';
        $file_name = public_path().DIRECTORY_SEPARATOR.$path_file_name;

        if(file_exists($file_name)){
            $data = file_get_contents($file_name);
            $data = json_decode($data);
            $last_date = $data[0];
        } else {
            $upload_date_data     = "CALL get_last_upload_date()";
            $upload_date_data_arr = callCustomSP($upload_date_data);
            $last_date = $upload_date_data_arr[0]['last_upload_date'];
            $updated_date = [ 0 => $last_date];
            WriteJsonInFile($updated_date, GET_LAST_UPDATED_DATE);
        }
        return ['status' => 1,'msg' => "OK", "data" => $last_date];
    }

    public function getEconomicsScatterChart(Request $request)
    {
        $tickerType = 1;
        $status = 1;
        $msg = "OK";
        
        $data = [];
        
        $country = $request->get("country");
        $tid = $request->get("tid");
        $current_ticker = $request->get("current_ticker");        
        $month_id = $request->get("month_id");
        $price_id = $request->get("price_id");
        $duration = $request->get("duration");
        $benchmark_id = $request->get("benchmark_id");

        if(!empty($current_ticker))
        {
            $tickerType = $current_ticker;
        }   

        if(empty($month_id))
        {
            $month_id = date("Y-m-d");
        }

        // $month_id = "2018-03-24";

        $history_data   = "CALL select_bond_scatter_data(".$country.", '".$month_id."',".$tickerType.")";
        $history_data  = callCustomSP($history_data);
        $rows = [];


        $i = 0;
        if(!empty($history_data))
        {
            foreach($history_data as $row)
            {
                $price = $row['last_price'];
                $category = $row['dur_adj_mid'];                                
                $extraTitle = date("d M, Y", strtotime($row['maturity_date']));

                if($price_id == 2)
                {
                    $price = $row[strtolower('YLD_YTM_MID')];
                }
                else if($price_id == 3)
                {
                    $price = $row[strtolower('Z_SPRD_MID')];
                }

                if($duration == 1)
                {
                    $category = $row['maturity_date'];

                    $date = new \DateTime($row['maturity_date']);
                    $category = $date->format("d M, Y");

                    // echo "<br />";
                    // echo $row['maturity_date']." => ".$category;

                }    
                else if($duration == 2)
                {
                    $category = $row['dur_adj_mid'];
                }   

                $rows[$i]['category'] = $category;
                $rows[$i]['price'] = $price;
                $rows[$i]['tooltip'] = $extraTitle;
                $i++;
            }    
        }

        $data['history_data'] = $rows;        

        $data['benchmark_history_data'] = [];

        if($benchmark_id > 0)
        {
            $history_data   = "CALL select_bond_scatter_data(".$benchmark_id.", '".$month_id."',".$tid.")";
            $dataTemp           = callCustomSP($history_data);
            $benchmark_history_data = $dataTemp;            

            if(true)
            {
                $dataKeys = [];
                $i = 0;
                foreach($data['history_data'] as $row)
                {
                    $dataKeys[$i]['title1'] = $row['category'];
                    $dataKeys[$i]['price1'] = $row['price'];                        
                    $dataKeys[$i]['title2'] = "";                    
                    $dataKeys[$i]['price2'] = NULL;                                                            
                    $i++;
                }

                $i = 0;    
                
                foreach($benchmark_history_data as $row)
                {
                    $price = $row['last_price'];
                    $category = $row['dur_adj_mid'];                                
                    $extraTitle = date("d M, Y", strtotime($row['maturity_date']));

                    if($price_id == 2)
                    {
                        $price = $row[strtolower('YLD_YTM_MID')];
                    }
                    else if($price_id == 3)
                    {
                        $price = $row[strtolower('Z_SPRD_MID')];
                    }

                    if($duration == 1)
                    {
                        $category = $row['maturity_date'];
                        $date = new \DateTime($row['maturity_date']);
                        $category = $date->format("d M, Y");
                    }    
                    else if($duration == 2)
                    {
                        $category = $row['dur_adj_mid'];
                    }   

                    $dataKeys[$i]['title2'] = $category;
                    $dataKeys[$i]['price2'] = $price;

                    if(!isset($dataKeys[$i]['title1']))
                    {
                        $dataKeys[$i]['title1'] = $category;                    
                        $dataKeys[$i]['price1'] = NULL;
                    }

                    $i++;                                                            
                }

                $data['benchmark_history_data'] = $dataKeys;
            }
        }

        return ["status" => $status, "msg" => $msg, "data" => $data];
    }
}
