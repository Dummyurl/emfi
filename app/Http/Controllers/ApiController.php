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
use App\models\Country;

class ApiController extends Controller
{
    public function __construct()
    {
        
    }

    public function getRelValData(Request $request)
    {
        $returnData['relval_chart'] = [];

        $relvalMonth = $request->get("relvalMonth");
        // $relvalMonth = "2018-03-30";

        
        $relvalPrice = $request->get("relvalPrice");
        $relvalRating = $request->get("relvalRating");
        $relvalCreditEquity = $request->get("relvalCreditEquity");
        $relval_chart = callCustomSP('CALL select_relval_chart_data('.$relvalCreditEquity.',"'.$relvalMonth.'",0)');


        if($relvalMonth == date("Y-m-d"))
        {
            if(empty($relval_chart))
            {
                $relvalMonth = \App\Models\Securities::max("created");
                $relval_chart = callCustomSP('CALL select_relval_chart_data('.$relvalCreditEquity.',"'.$relvalMonth.'",0)');                
            }            
        }        

        // dd($relval_chart);

        $status = 1;
        $msg = "OK";
        $data = [];
        
        $finalArray =  [];
        if(!empty($relval_chart))
        {
            $i = 0;
            foreach($relval_chart as $r)
            {
                if($relvalRating == 1)
                {
                    $data[$i]['category'] = $r['rtg_sp'];
                }
                else 
                {
                    $data[$i]['category'] = $r['current_oecd_member_cor_class'];
                }

                if($relvalPrice == 1)
                {
                    $data[$i]['price'] = $r['last_price'];   
                }    
                else if($relvalPrice == 2)
                {
                    $data[$i]['price'] = $r['YLD_YTM_MID'];
                }    
                else if($relvalPrice == 3)
                {
                    $data[$i]['price'] = $r['Z_SPRD_MID'];
                }    

                $data[$i]['country_title'] = ucwords(strtolower($r['country_title']));
                $data[$i]['security_name'] = $r['security_name'];
                $data[$i]['created_format'] = $r['created_format'];
                $data[$i]['country_code'] = $r['country_code'];
                

                $i++;
            }     
            
            $i = 0;
            foreach($data as $r)
            {
                $finalArray[$r['category']][] = ['price' => $r['price'], 'country_title' => $r['country_title'], 'country_code' => $r['country_code']];
            }       

//            echo "<pre>";
//            print_r($finalArray);
            
            // if($relvalRating == 1)
            // {                
            //     ksort($finalArray);
            // }   
            // else
            // {
            //     krsort($finalArray);                
            // }                       
            
            //  $finalArray = array_reverse($finalArray);            
            //            echo "<pre>";
            //            print_r($finalArray);
            //            exit;
        }           

        return ['status' => $status, "msg" => $msg, "data" => $finalArray];
    }

    public function getAreaChart(Request $request)
    {
        $id1 = $request->get("id1",0);
        $id2 = $request->get("id2",0);

        $areaMonth = $request->get("areaMonth");
        $areaPrice = $request->get("areaPrice");

        $security1 = \App\Models\Securities::find($id1);
        $security2 = \App\Models\Securities::find($id2);

        if(!$security1 || !$security2)
        {
            return ["status" => 0, "msg" => "Security not found !"];
        }

        $isEquity = 0;
        if($security1->market_id != 5 || $security2->market_id != 5)
        {
            $isEquity = 1;
        }        


        if($isEquity == 1 && $areaPrice == 3)
        {
            $areaPrice = 1;
        }

        $month_id = 1;        
        $data = [];        
        
        $main_title = '';
        $global_security_title1 = '';
        $global_security_title2 = '';


        if($security1 && $security2)
        {
            $main_title = $security1->security_name."<br /><span>".$security2->security_name."</span>";
        }


        $area_chart = callCustomSP('CALL select_analyzer_bond_data('.$id1.','.$id2.','.$areaMonth.')');
        if(!empty($area_chart))
        {
            foreach($area_chart as $key => $val)
            {
                $area_chart[$key]['created_format'] = date("d M Y",strtotime($area_chart[$key]['created']));   

                if($areaPrice == 1)                
                $area_chart[$key]['main_price'] = $area_chart[$key]['price_difference'];

                if($areaPrice == 2)                
                $area_chart[$key]['main_price'] = $area_chart[$key]['YLD_difference'];

                if($areaPrice == 3)                
                $area_chart[$key]['main_price'] = $area_chart[$key]['Z_difference'];
            }    
        }

        $returnData['area_chart'] = $area_chart;
        $security_id    = $id1;
        $month_id       =  $request->get("historyMonth", 1);
        $benchmark_id       =  $id2;
        $price_id = $request->get("historyPrice");
        $market_id = $request->get("market_id");
        $history_data   = "CALL select_security_historical_data(".$id1.", ".$month_id.")";
        $dataTemp           = callCustomSP($history_data);        
        $returnData['history_data'] = $dataTemp;
        $returnData['benchmark_history_data'] = [];

        if($isEquity == 1 && $price_id == 3)
        {
            $price_id = 1;
        }

                
        if($benchmark_id > 0)
        {
            $history_data   = "CALL select_security_historical_data(".$benchmark_id.", ".$month_id.")";
            $dataTemp           = callCustomSP($history_data);
            $benchmark_history_data = $dataTemp;            
            if(!empty($benchmark_history_data))
            {
                $dataKeys = [];

                foreach($returnData['history_data'] as $row)
                {
                    if($price_id != 1)
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

                    $dataKeys[$row['created']]['column2'] = NULL;
                    $dataKeys[$row['created']]['date'] = $row['created_format'];
                }

                foreach($benchmark_history_data as $row)
                {
                    if($price_id != 1)
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
                    $dataKeys[$row['created']]['column1'] = NULL;

                    $dataKeys[$row['created']]['date'] = $row['created_format'];                                                             
                }


                ksort($dataKeys);


                $i = 0;

                foreach($dataKeys as $key => $val)
                {
                    if(!empty($dataKeys[$key]['date']))
                    {
                        $finalData[$i] = [$dataKeys[$key]['date'], $dataKeys[$key]['column1'], $dataKeys[$key]['column2']];

                        $i++;                        
                    }    
                }

                $returnData['benchmark_history_data'] = $finalData;
            }
        }   

        $returnData['regression_chart'] = [];
        $regressionMonth = $request->get("regressionMonth");
        $regressionPrice = $request->get("regressionPrice");

        if($isEquity == 1 && $regressionPrice == 3)
        {
            $regressionPrice = 1;
        }
        

        $regression_chart = callCustomSP('CALL select_analyzer_bond_data('.$id1.','.$id2.','.$regressionMonth.')');
        if(!empty($regression_chart))
        {
            $i = 1;
            $counterMain = count($regression_chart);
            foreach($regression_chart as $key => $val)
            {
                $regression_chart[$key]['created_format'] = date("d M Y",strtotime($regression_chart[$key]['created']));   

                if($regressionPrice == 1)
                {
                    $regression_chart[$key]['main_price'] = $regression_chart[$key]['last_price'];
                    $regression_chart[$key]['main_price2'] = $regression_chart[$key]['last_price2'];    
                }                                
                else if($regressionPrice == 2)
                {
                    $regression_chart[$key]['main_price'] = $regression_chart[$key]['YLD_YTM_MID'];
                    $regression_chart[$key]['main_price2'] = $regression_chart[$key]['YLD_YTM_MID2'];
                }                
                else if($regressionPrice == 3)
                {
                    $regression_chart[$key]['main_price'] = $regression_chart[$key]['Z_SPRD_MID'];
                    $regression_chart[$key]['main_price2'] = $regression_chart[$key]['Z_SPRD_MID2'];    
                }    

                if($i == $counterMain)
                $regression_chart[$key]['is_recent'] = 1;                            
                else 
                $regression_chart[$key]['is_recent'] = 0;                            

                $i++;                
            }

            $returnData['regression_chart'] = $regression_chart;    
        }



        return 
        [
            'status' => 1,
            'msg' => "OK", 
            "data" => $returnData,            
            "main_title" => $main_title, 
            "global_security_title1" => $global_security_title1,
            "global_security_title2" => $global_security_title2,
            "isEquity" => $isEquity,
        ];
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

    public function getEconomicsGeoChart(Request $request, $market_id)
    {
        $status = 1;
        $msg = "OK";
        $data = [];
        
        $data['countries'] = callCustomSP('CALL select_country_geo_chart_data('.$market_id.')');        
        
        $rows = [];

        $colors = [];
        $values = [];

        $i = 0;
        foreach($data['countries'] as $r)
        {
            $values[$i] = $r['avg_percentage_change'];
            // $colors[] = $this->getColor($r['avg_percentage_change']);
            $i++;
        }

        sort($values);
        
        $i = 0;

        foreach($values as $val)
        {
            $colors[$i] = $this->getColor($val);
            $i++;
        }

        // echo "<pre>";
        // print_r($values);
        // echo "</pre>";

        // sort($values);

        // echo "<pre>";
        // print_r($values);
        // echo "</pre>";

        $data['colors'] = $colors;
        $data['values'] = $values;
        
        return ['status' => $status, 'msg' => $msg, 'data' => $data];
    }

    public function getColor($val)
    {
        if($val >= 0)
        {
            if($val > 2)
            {
                $val = 2;                
            }    

            $color = "#00ff00";
            $steps = round((245*$val) / 2);
            $steps = 245 - $steps;
            
            // $newSteps = $steps * 3;

            $newColor = adjustBrightness($color, $steps);
        }
        else
        {
            $val = abs($val);

            if($val > 2)
            {
                $val = 2;                
            }    

            $color = "#ff0000";
            $steps = round((245*$val) / 2);
            $steps = 245 - $steps;
            // $steps = $steps * 3;
            $newColor = adjustBrightness($color, $steps);
        }

        return $newColor;
    }

    public function TopGainer(Request $request, $market_id = null)
    {
        $market_data  = "CALL select_Top_Gainer(".$market_id.",2)";
        $gainer_data = callCustomSP($market_data);
        $returnData['top_gainer'] = $gainer_data;
        
        if(isset($gainer_data[0])){
            $security_id = $gainer_data[0]['id']; 
            $month_id = 12; 
            $benchmark = '';
            $market_data  = "CALL select_security_historical_data(".$security_id.", ".$month_id.")";
            $History_data = callCustomSP($market_data);
            $returnData['gainer_history_data'] = $History_data;

            $security_id = $gainer_data[0]['id'];
            $banchmark_data  = "CALL select_security_banchmark(".$security_id.")";
            $banchmark_data_arr = callCustomSP($banchmark_data);
            $returnData['arr_banchmark'] = $banchmark_data_arr;
        }

        $market_data  = "CALL select_Top_Loser(".$market_id.",2)";
        $loser_data = callCustomSP($market_data);
        $returnData['top_loser'] = $loser_data;
        if(isset($loser_data[0])){
            $security_id = $loser_data[0]['id']; $month_id = 12; $benchmark = '';
            $market_data  = "CALL select_security_historical_data(".$security_id.", ".$month_id.")";
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

        $from = $request->get("from");
        $isEquity = 0;
        $s_title = '';
        $s_title2 = '';

        if($from == "default_market")
        {
            $security = \App\Models\Securities::find($security_id);
            if($security)
            {      
                $s_title = $security->security_name;
                $market_id = $security->market_id;          
                if($market_id != 5)
                {
                    $isEquity = 1;
                    if($price_id == 3)
                    {
                        $price_id = 1;
                    }
                }        
            }
        }

        $history_data   = "CALL select_security_historical_data(".$security_id.", ".$month_id.")";
        $data           = callCustomSP($history_data);
        $returnData['history_data'] = $data;

        $returnData['benchmark_history_data'] = [];

        if($benchmark_id > 0)
        {
            $security2 = \App\Models\Securities::find($benchmark_id);
            if($security2)
            {
                $s_title2 = $security2->security_name;
            }

            $history_data   = "CALL select_security_historical_data(".$benchmark_id.", ".$month_id.")";
            $data           = callCustomSP($history_data);
            $benchmark_history_data = $data;            

            if(!empty($benchmark_history_data))
            {
                $dataKeys = [];

                foreach($returnData['history_data'] as $row)
                {
                    if($price_id != 1)
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

                    $dataKeys[$row['created']]['column2'] = NULL;
                    $dataKeys[$row['created']]['date'] = $row['created_format'];
                    $dataKeys[$row['created']]['main_date'] = $row['created'];
                }

                foreach($benchmark_history_data as $row)
                {
                    if($price_id != 1)
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
                    $dataKeys[$row['created']]['column1'] = NULL;

                    $dataKeys[$row['created']]['main_date'] = $row['created'];                                                             
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
                        $finalData[$i] = [$dataKeys[$key]['date'], $dataKeys[$key]['column1'], $dataKeys[$key]['column2'], $dataKeys[$key]['main_date']];

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

        $banchmark_data     = "CALL select_security_banchmark(".$security_id.")";
        $banchmark_data_arr = callCustomSP($banchmark_data);
        $returnData['arr_banchmark'] = $banchmark_data_arr;

        return ['status' => 1,'msg' => "OK", "data" => $returnData,'title' => $s_title, 'title2' => $s_title2, 'isEquity' => $isEquity];
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
            $history_data   = "CALL select_security_historical_data(".$benchmark_id.", ".$month_id.")";
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
        
        return ["status" => $status, "msg" => $msg, "data" => $data, 'isEquity' => $isEquity];
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

        $history_data   = "CALL select_counrty_yield_curve_bond_data(".$country.", '".$month_id."',".$tickerType.")";
        $history_data  = callCustomSP($history_data);
        $rows = [];


        $i = 0;
        if(!empty($history_data))
        {
            foreach($history_data as $row)
            {
                $price = $row['last_price'];
                $category = $row['dur_adj_mid'];                                
                $extraTitle = $row['security_name'];
                

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
                $rows[$i]['date_difference'] = $row['date_difference'];
                $i++;
            }    
        }

        $data['history_data'] = $rows;        

        $data['benchmark_history_data'] = [];

        if($benchmark_id > 0)
        {
            $history_data   = "CALL select_counrty_yield_curve_bond_data(".$benchmark_id.", '".$month_id."',".$tid.")";
            $dataTemp           = callCustomSP($history_data);
            $benchmark_history_data = $dataTemp;            

            if(true)
            {
                $dataKeys = [];
                $i = 0;
                foreach($data['history_data'] as $row)
                {
                    $dataKeys[$i]['title1'] = $row['category'];
                    $dataKeys[$i]['date_difference'] = $row['date_difference'];
                    $dataKeys[$i]['price1'] = $row['price'];
                    $dataKeys[$i]['tooltip'] = $row['tooltip'];                        
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
                    $dataKeys[$i]['tooltip2'] = $row['security_name'];

                    if(!isset($dataKeys[$i]['title1']))
                    {
                        $dataKeys[$i]['tooltip'] = "";
                        $dataKeys[$i]['title1'] = $category;                    
                        $dataKeys[$i]['price1'] = NULL;
                        $dataKeys[$i]['date_difference'] = $row['date_difference'];
                    }

                    $i++;                                                            
                }

                $data['benchmark_history_data'] = $dataKeys;
            }
        }

        return ["status" => $status, "msg" => $msg, "data" => $data];
    }
}
