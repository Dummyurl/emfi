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
            $security_id = $gainer_data[0]['id']; $month_id = 3; $benchmark = '';
            $market_data  = "CALL Select_Historical_Data(".$security_id.", ".$month_id.", '".$benchmark."')";
            $History_data = callCustomSP($market_data);
            $returnData['gainer_history_data'] = $History_data;
        }
        
        $market_data  = "CALL select_Top_Loser(".$market_id.")";
        $loser_data = callCustomSP($market_data);
        $returnData['top_loser'] = $loser_data;
        if(isset($loser_data[0])){
            $security_id = $loser_data[0]['id']; $month_id = 3; $benchmark = '';
            $market_data  = "CALL Select_Historical_Data(".$security_id.", ".$month_id.", '".$benchmark."')";
            $History_data = callCustomSP($market_data);
            $returnData['loser_history_data'] = $History_data;
        }

        return ['status' => 1,'msg' => "OK", "data" => $returnData];
    }

    public function HistoryChart(Request $request)
    {
        $security_id = $request->get("security_id");
        $month_id =  $request->get("month_id");
        $benchmark =  $request->get("benchmark");

        $market_data  = "CALL Select_Historical_Data(".$security_id.", ".$month_id.", ".$benchmark.")";
        $data = callCustomSP($market_data);
        $r_data = json_encode($data);
        return ['status' => 1,'msg' => "OK", "data" => $r_data];
    }
}
