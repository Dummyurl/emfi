<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\MarketType;
use App\models\Country;
use App\Models\HomeSlider;

class PagesController extends Controller {

    public function __construct() {

    }

    public function home(Request $request) {

        $data = array();
        $data['page_title'] = "EMFI: Home Page";
        $locale = session('locale');
        
        if (empty($locale)) {
            $locale = 'en';
        }
        app()->setLocale($locale);
        $sliders = HomeSlider::getHomeSliders(15);
        $slider_rows = [];

        $i = 0;

        foreach($sliders as $slider)
        {
            $title = $slider->title;
            $description = $slider->description;
            if(empty($slider->title) && $slider->title == '')
            {
                $title = $slider->translate('en',true)->title;
                if(empty($title))
                    $title = $slider->translate('es',true)->title;                
            }

            if(empty($slider->description) && $slider->description == '')
            {
                $description = $slider->translate('en',true)->description;   

                if(empty($description))
                $description = $slider->translate('es',true)->description;                
            }

            // Get Chart Data
            $chart_data = $this->getChartData($slider);
            $slider_rows[$i]['id'] = $slider->id;
            $slider_rows[$i]['title'] = $title;
            $slider_rows[$i]['description'] = $description;
            $slider_rows[$i]['chart_data'] = $chart_data;
            $slider_rows[$i]['graph_type'] = $slider->graph_type;
            $slider_rows[$i]['option_banchmark'] = $slider->option_banchmark;
            $slider_rows[$i]['option_prices'] = $slider->option_prices;
            $slider_rows[$i]['country_id'] = $slider->country_id;
            $i++;
        }   

        $data['sliders'] = $slider_rows;
        $data['last_update_date'] = getLastUpdateDate();
        return view('welcome', $data);
    }

    public function getChartData($slider)
    {
        $chart_data = [];
        if($slider->graph_type == "market_movers_gainers")
        {
            $market_id = $slider->option_market;
            $market_data  = "CALL select_Top_Gainer(".$market_id.",0)";
            $gainer_data = callCustomSP($market_data);
            $chart_data = $gainer_data;
        }
        else if($slider->graph_type == "market_movers_laggers")
        {
            $market_id = $slider->option_market;
            $market_data  = "CALL select_Top_Loser(".$market_id.",0)";
            $chart_data = callCustomSP($market_data);
        }else if($slider->graph_type == "market_history")
        {
            $chart_data = $this->GetMarketHistoryData($slider);
        }else if($slider->graph_type == "yield_curve")
        {
            $chart_data = $this->GetYieldCurveChartData($slider);
        } else if ($slider->graph_type == "differential")
        {
            $chart_data = $this->GetDifferentialChartData($slider);
        } else if ($slider->graph_type == "regression")
        {
            $chart_data = $this->GetRegressionChartData($slider);
        } 
        else if ($slider->graph_type == "relative_value")
        {
            $chart_data = $this->GetRelativeValueChartData($slider);
        }
        return $chart_data;
    }

    public function economics(Request $request, $country = "") {
        $data = array();
        $data['page_title'] = "EMFI: Countries";
		$locale = session('locale');
		if (empty($locale)) {
			$locale = 'en';
		}

		app()->setLocale($locale);

        if (!empty($country)) {
            $defaultCountry = $country;
        } else 
        {
            $data = [];
            $data['last_update_date'] = getLastUpdateDate();
            $data['page_title'] = "EMFI: Countries";
            $data['markets'] = MarketType::getArrayList();
            $data['countries'] = Country::where("country_type",2)->orderBy("title")->get()->toArray();
            $data['countries'] = json_encode($data['countries']);            
            return view('defaultCountryPage', $data);                    
        }

        $data['countryObj'] = Country::where("slug", $defaultCountry)->first();

        if (!$data['countryObj']) {
            abort(404);
        }

        $data['market_boxes'] = callCustomSP('CALL select_economics_country(' . $data['countryObj']->id . ')');
        $bond_data = callCustomSP('CALL select_economic_bond(' . $data['countryObj']->id . ')');
        $data['countries'] = Country::where("country_type",2)->orderBy("title")->get();
        $data['bond_data'] = [];

        foreach ($bond_data as $r) {
            $data['bond_data'][$r['ticker']][] = $r;
        }

        $data['country_benchmarkes'] = \App\Models\Tickers::getCountriesList();
        // dd($data['country_benchmarkes']);
        $data['number_of_charts'] = count(array_keys($data['bond_data']));
        $data['tweets'] = getSearchTweets($data['countryObj']->title);
        $data['last_update_date'] = getLastUpdateDate();
        return view('economics', $data);
    }

    public function contact(Request $request) {
        $data = array();
        $data['page_title'] = "EMFI: Contact";
        $locale = session('locale');
        if (empty($locale)) {
            $locale = 'en';
        }
        app()->setLocale($locale);

        return view('contact', $data);
    }

    public function about(Request $request) {
        $data = array();
        $data['page_title'] = "EMFI: About";
        $locale = session('locale');
        if (empty($locale)) {
            $locale = 'en';
        }

        app()->setLocale($locale);
        return view('about', $data);
    }

    public function analyzer(Request $request, $type = '') 
    {
        $data = array();
        $data['page_title'] = "EMFI: Analyzer";
        $data['page_type'] = $type;
        
        $locale = session('locale');
        if (empty($locale)) 
        {
            $locale = 'en';
        }        

	    app()->setLocale($locale);
        
        $data['last_update_date'] = getLastUpdateDate();
        $treeMapData = callCustomSP('CALL select_analyzer_tree_map_data(0)');
        $data['treeMap'] = [];

        $equities = [];
        $credits = [];
        foreach($treeMapData as $r)
        {
            if($r['market_id'] == 5)
            {
                $credits['countries'][$r['country']]['title'] = $r['country_name'];
                $credits['countries'][$r['country']]['records'][] = ['id' => $r['id'],'security_name' => $r['security_name'],'data' => $r];
            }
            else
            {
                $equities['countries'][$r['country']]['title'] = $r['country_name'];
                $equities['countries'][$r['country']]['records'][] = ['id' => $r['id'],'security_name' => $r['security_name'],'data' => $r];
            }
        }

        $data['equities'] = $equities;
        $data['credits'] = $credits;

        $default_security_id1 = 0;
        $default_security_id2 = 0;        
        $treeMapData_default = callCustomSP('CALL select_analyzer_tree_map_data(1)');

        if(isset($treeMapData_default[0]) && !empty(($treeMapData_default[0])))
        {
            $default_security_id1 = $treeMapData_default[0]['id'];
        }

        if(isset($treeMapData_default[1]) && !empty(($treeMapData_default[1])))
        {
            $default_security_id2 = $treeMapData_default[1]['id'];
        }
        
        $data['default_security_id1'] = $default_security_id1;
        $data['default_security_id2'] = $default_security_id2;
        return view('analyzer', $data);
    }    

    public function market(Request $request, $type = '') {

        $main_categories = [
            "equities" => 1,
            "currencies" => 2,
            "commodities" => 3,
            "rates" => 4,
            "credit" => 5,
        ];

		$locale = session('locale');
		if (empty($locale)) {
			$locale = 'en';
		}

		app()->setLocale($locale);

        $data = array();
        $data['page_title'] = "EMFI: Markets";
        // $data['tweets'] = getLatestTweets();
        $from = "@emfisecurities";
        $data['tweets'] = getPeopleTweets($from);

        $data['markets'] = MarketType::getArrayList();
        $data['market_boxes'] = callCustomSP('CALL select_market()');
        // dd($data['market_boxes']);

        $data['selected_market'] = isset($main_categories[$type]) ? $main_categories[$type] : 1;
        // dd($data['market_boxes']);

        $data['last_update_date'] = getLastUpdateDate();

        if(!empty($type))
        {
            return view('market', $data);
        }
        else
        {
            $treeMapData = callCustomSP('CALL select_analyzer_tree_map_data(0)');
            
            $equities = [];
            $credits = [];
            foreach($treeMapData as $r)
            {
                if($r['market_id'] == 5)
                {
                    $credits['countries'][$r['country']]['title'] = $r['country_name'];
                    $credits['countries'][$r['country']]['records'][] = ['id' => $r['id'],'security_name' => $r['security_name'],'data' => $r];
                }
                else
                {
                    $equities['countries'][$r['country']]['title'] = $r['country_name'];
                    $equities['countries'][$r['country']]['records'][] = ['id' => $r['id'],'security_name' => $r['security_name'],'data' => $r];
                }
            }

            $data['equities'] = $equities;
            $data['credits'] = $credits;            
            return view('marketDefault', $data);
        }
    }

    public function terms_of_uses() {
        $locale = session('locale');
        if (empty($locale)) {
            $locale = 'en';
        }
        $pageID = "TERMS_OF_USES";
        $data = array();
        $data['page_title'] = "EMFI: Terms Of Uses";
        app()->setLocale($locale);
        $content = \App\Models\CmsPage::where('page_constant', $pageID)->first();
        if (!$content) {
            return abort(404);
        }
        $data['content'] = $content;
        return view('terms_of_uses', $data);
    }

    public function privacy_statements() {
        $locale = session('locale');
        if (empty($locale)) {
            $locale = 'en';
        }
        $pageID = "PRIVACY_STATEMENTS";
        $data = array();
        $data['page_title'] = "EMFI: Privacy Statements";
        app()->setLocale($locale);
        $content = \App\Models\CmsPage::where('page_constant', $pageID)->first();
        if (!$content) {
            return abort(404);
        }
        $data['content'] = $content;
        return view('privacy_statments', $data);
    }

    public function cookies() {
        $locale = session('locale');
        if (empty($locale)) {
            $locale = 'en';
        }
        $pageID = "COOKIES";
        $data = array();
        $data['page_title'] = "EMFI: Cookies";

        app()->setLocale($locale);

        $content = \App\Models\CmsPage::where('page_constant', $pageID)->first();
        if (!$content) {
            return abort(404);
        }
        $data['content'] = $content;
        return view('cookies', $data);
    }

    public function change_locale($locale) {
        $languages = \App\Custom::getLanguages();
        if (isset($languages[$locale]) && !empty($languages[$locale])) {
            session(['locale' => $locale]);
            return redirect()->back();
        } else {
            return redirect('/');
        }
    }


    public function GetMarketHistoryData($slider){
        
        $benchmark_id   = $slider->option_banchmark;
        $security_id    = $slider->security_id;
        $month_id       = $slider->option_period;
        $price_id       = $slider->option_prices;
        
        $chart_data['options']['option_banchmark']  = $slider->option_banchmark;
        $chart_data['options']['security_id']       = $slider->security_id;
        $chart_data['options']['option_period']     = $slider->option_period;
        $chart_data['options']['option_prices']     = $slider->option_prices;

        $SP_data = "CALL select_security_historical_data(".$security_id.", ".$month_id.")";
        $history_data = callCustomSP($SP_data);
        $chart_data['history_data'] = $history_data;
        $chart_data['benchmark_history_data'] = [];
        if($benchmark_id > 0)
        {
            $history_data = "CALL select_security_historical_data(".$benchmark_id.", ".$month_id.")";
            $data           = callCustomSP($history_data);
            $benchmark_history_data = $data;
            if(!empty($benchmark_history_data))
            {
                $dataKeys = [];
                foreach($chart_data['history_data'] as $row)
                {
                    if($price_id == 2)
                    {
                        $dataKeys[$row['created']]['column1'] = $row['YLD_YTM_MID'];
                    }   
                    else if($price_id == 3)
                    {
                        $dataKeys[$row['created']]['column1'] = $row['Z_SPRD_MID'];
                    }else
                    {
                        $dataKeys[$row['created']]['column1'] = $row['last_price'];
                    }
                    $dataKeys[$row['created']]['column2'] = NULL;
                    $dataKeys[$row['created']]['date'] = $row['created_format'];
                }

                foreach($benchmark_history_data as $row)
                {
                    if($price_id == 2)
                    {
                        $dataKeys[$row['created']]['column2'] = $row['YLD_YTM_MID'];
                    }   
                    else if($price_id == 3)
                    {
                        $dataKeys[$row['created']]['column2'] = $row['Z_SPRD_MID'];
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
                $chart_data['benchmark_history_data'] = $finalData;
            }
        }
        return $chart_data;
    }

    public function GetYieldCurveChartData($slider){
        $country = $slider->country_id;
        $month_id       = $slider->option_period;
        $benchmark_id   = $slider->option_banchmark;
        $price_id       = $slider->option_prices;
        $duration       = $slider->option_maturity;
        $tickerType = 1;
        $tid = 1;
        $data['options']['option_period'] = $slider->option_period;
        $data['options']['option_banchmark'] = $slider->option_banchmark;
        $data['options']['option_prices'] = $slider->option_prices;
        $data['options']['option_maturity'] = $slider->option_maturity;

        $month_id =  date("Y-m-d", strtotime("-".$month_id." months"));
        // $month_id = '2017-04-07'; 
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
        $chart_data = $data;

        return $chart_data;
    }

    public function GetDifferentialChartData($slider){

        $security_id        = $slider->security_id;
        $option_security    = $slider->option_security;
        $option_prices      = $slider->option_prices;
        $option_period      = $slider->option_period;

        $security1 = \App\Models\Securities::find($security_id);
        $security2 = \App\Models\Securities::find($option_security);
        
        $isEquity = 0;
        if($security1->market_id != 5 || $security2->market_id != 5)
        {
            $isEquity = 1;
        }
        if($isEquity == 1 && $option_prices == 3)
        {
            $option_prices = 1;
        }

        $data['options']['security_id']     = $slider->security_id;
        $data['options']['option_security'] = $slider->option_security;
        $data['options']['option_prices']   = $slider->option_prices;
        $data['options']['option_period']   = $slider->option_period;
        $SP_call = 'CALL select_analyzer_bond_data('.$security_id.','.$option_security.','.$option_period.')';
        $area_chart = callCustomSP($SP_call);
        if(!empty($area_chart))
        {
            foreach($area_chart as $key => $val)
            {
                $area_chart[$key]['created_format'] = date("d M Y",strtotime($area_chart[$key]['created']));

                if($option_prices == 1){
                    $area_chart[$key]['main_price'] = $area_chart[$key]['price_difference'];
                }
                if($option_prices == 2){
                    $area_chart[$key]['main_price'] = $area_chart[$key]['YLD_difference'];
                }
                if($option_prices == 3){
                    $area_chart[$key]['main_price'] = $area_chart[$key]['Z_difference'];
                }
            }
        }
        $data['area_chart'] = $area_chart;
        $chart_data = $data;

        return $chart_data;
    }

    public function GetRegressionChartData($slider){
        $security_id        = $slider->security_id;
        $option_security    = $slider->option_security;
        $option_prices      = $slider->option_prices;
        $option_period      = $slider->option_period;

        $security1 = \App\Models\Securities::find($security_id);
        $security2 = \App\Models\Securities::find($option_security);
        
        $isEquity = 0;
        if($security1->market_id != 5 || $security2->market_id != 5)
        {
            $isEquity = 1;
        }
        if($isEquity == 1 && $option_prices == 3)
        {
            $option_prices = 1;
        }

        $data['options']['security_id']     = $slider->security_id;
        $data['options']['option_security'] = $slider->option_security;
        $data['options']['option_prices']   = $slider->option_prices;
        $data['options']['option_period']   = $slider->option_period;
        
        $SP_Call = 'CALL select_analyzer_bond_data('.$security_id.','.$option_security.','.$option_period.')';
        $regression_chart = callCustomSP($SP_Call);
        if(!empty($regression_chart))
        {
            $i = 1;
            $last_count = count($regression_chart);
            foreach($regression_chart as $key => $val)
            {
                $regression_chart[$key]['created_format'] = date("d M Y",strtotime($regression_chart[$key]['created']));   

                if($option_prices == 1)
                {
                    $regression_chart[$key]['main_price'] = $regression_chart[$key]['last_price'];
                    $regression_chart[$key]['main_price2'] = $regression_chart[$key]['last_price2'];    
                }                                
                else if($option_prices == 2)
                {
                    $regression_chart[$key]['main_price'] = $regression_chart[$key]['YLD_YTM_MID'];
                    $regression_chart[$key]['main_price2'] = $regression_chart[$key]['YLD_YTM_MID2'];
                }                
                else if($option_prices == 3)
                {
                    $regression_chart[$key]['main_price'] = $regression_chart[$key]['Z_SPRD_MID'];
                    $regression_chart[$key]['main_price2'] = $regression_chart[$key]['Z_SPRD_MID2'];    
                }    

                if($i == $last_count)
                $regression_chart[$key]['is_recent'] = 1;
                else 
                $regression_chart[$key]['is_recent'] = 0;

                $i++;                
            }
            $data['regression_chart'] = $regression_chart;
        }
        $chart_data = $data;

        return $chart_data;
    }

    public function GetRelativeValueChartData($slider){
        $country_id        = $slider->country_id;
        $option_period      = $slider->option_period;
        $option_prices      = $slider->option_prices;
        $option_rating      = $slider->option_rating;
        $option_credit        = $slider->option_credit;

        $data['options']['country_id']      = $slider->country_id;
        $data['options']['option_period']   = $slider->option_period;
        $data['options']['option_prices']   = $slider->option_prices;
        $data['options']['option_rating']   = $slider->option_rating;
        $data['options']['option_credit']   = $slider->option_credit;

        $relvalMonth =  date("Y-m-d", strtotime("-".$option_period." months"));
        $relvalMonth =  "2018-04-16";
        $query = 'CALL select_relval_chart_data('.$option_credit.',"'.$relvalMonth.'")';
        
        $relval_chart = callCustomSP($query);

        if($relvalMonth == date("Y-m-d"))
        {
            if(empty($relval_chart))
            {
                $relvalMonth = \App\Models\Securities::max("created");
                $relval_chart = callCustomSP('CALL select_relval_chart_data('.$option_credit.',"'.$relvalMonth.'")');
            }
        }
        $data1 = [];
        $finalArray =  [];
        if(!empty($relval_chart))
        {
            $i = 0;
            foreach($relval_chart as $r)
            {
                if($option_rating == 1)
                {
                    $data1[$i]['category'] = $r['rtg_sp'];
                } else {
                    $data1[$i]['category'] = $r['current_oecd_member_cor_class'];
                }

                if($option_prices == 1)
                {
                    $data1[$i]['price'] = $r['last_price'];   
                }    
                else if($option_prices == 2)
                {
                    $data1[$i]['price'] = $r['YLD_YTM_MID'];
                }    
                else if($option_prices == 3)
                {
                    $data1[$i]['price'] = $r['Z_SPRD_MID'];
                }    

                $data1[$i]['country_title'] = ucwords(strtolower($r['country_title']));
                $data1[$i]['security_name'] = $r['security_name'];
                $data1[$i]['created_format'] = $r['created_format'];
                $i++;
            }
            $i = 0;
            foreach($data1 as $r)
            {
                $finalArray[$r['category']][] = ['price' => $r['price'], 'country_title' => $r['country_title']];
            }

            if($option_rating == 1){
                ksort($finalArray);
            } else {
                krsort($finalArray);
            }
        }
        $data['relative_data'] = $finalArray;
        $chart_data = $data;

        return $chart_data;
    }

}
