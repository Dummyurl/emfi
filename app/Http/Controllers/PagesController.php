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

    public function __construct()
    {
        $this->middleware('page_middleware');
    }

    public function home(Request $request) {

        if($request->get("action") == "cookie")
        {
            $check_performance = $request->get("check_performance");
            $check_user_preference = $request->get("check_user_preference");

            if($check_performance == 1)
                session(["check_performance" => 0]);
            else
                session(["check_performance" => 1]);

            if($check_user_preference == 1)
                session(["check_user_preference" => 0]);                
            else
                session(["check_user_preference" => 1]);                

            return ['status' => 1];            
        }

        $close_disclaimer = $request->get("close_disclaimer");
        if($close_disclaimer == 1)
        {
            session(["is_close_disclaimer" => 1]);
            return ['status' => 1];
        }
        $currentLang = \Request::segment(1);

        if(empty($currentLang))
        {
            return \Redirect::to('/english', 301);
        }

        $data = array();
        $data['page_title'] = "Home | EMFI Group";
        $locale = session('locale');
        if (empty($locale))
        {
            $locale = 'en';
        }
        app()->setLocale($locale);
        $sliders = HomeSlider::getHomeSliders(15);
        $slider_rows = [];

        $i = 0;

        foreach ($sliders as $slider) {
            $title = $slider->title;
            $description = $slider->description;
            if (empty($slider->title) && $slider->title == '') {
                $title = $slider->translate('en', true)->title;
                if (empty($title))
                    $title = $slider->translate('es', true)->title;
            }

            if (empty($slider->description) && $slider->description == '') {
                $description = $slider->translate('en', true)->description;

                if (empty($description))
                    $description = $slider->translate('es', true)->description;
            }
            $display_date = date('Y-m-d');
            if(isset($slider->display_date) && !empty($slider->display_date)){
                $display_date = $slider->display_date;
            }

            // Get Chart Data
            $chart_data = $this->getChartData($slider);
            $slider_rows[$i]['id'] = $slider->id;
            $slider_rows[$i]['title'] = $title;
            $slider_rows[$i]['description'] = $description;
            $slider_rows[$i]['chart_data'] = $chart_data;
            $slider_rows[$i]['graph_type'] = $slider->graph_type;
            $slider_rows[$i]['display_date'] = $display_date;
            $slider_rows[$i]['option_banchmark'] = $slider->option_banchmark;
            $slider_rows[$i]['option_prices'] = $slider->option_prices;
            $slider_rows[$i]['country_id'] = $slider->country_id;
            $i++;
        }

        $data['sliders'] = $slider_rows;
        $data['last_update_date'] = getLastUpdateDate();
        // return view('welcome', $data);
        return view('home', $data);
    }

    public function getChartData($slider) {
        $chart_data = [];
        if ($slider->graph_type == "market_movers_gainers") {
            $market_id = $slider->option_market;
            $market_data = "CALL select_Top_Gainer(" . $market_id . ",2)";
            $gainer_data = callCustomSP($market_data);
            $chart_data = $gainer_data;
        } else if ($slider->graph_type == "market_movers_laggers") {
            $market_id = $slider->option_market;
            $market_data = "CALL select_Top_Loser(" . $market_id . ",2)";
            $chart_data = callCustomSP($market_data);
        } else if ($slider->graph_type == "market_history") {
            $chart_data = $this->GetMarketHistoryData($slider);
        } else if ($slider->graph_type == "yield_curve") {
            $chart_data = $this->GetYieldCurveChartData($slider);
        } else if ($slider->graph_type == "differential") {
            $chart_data = $this->GetDifferentialChartData($slider);
        } else if ($slider->graph_type == "regression") {
            $chart_data = $this->GetRegressionChartData($slider);
        } else if ($slider->graph_type == "relative_value") {
            $chart_data = $this->GetRelativeValueChartData($slider);
        }
        return $chart_data;
    }

    public function economics(Request $request, $country = "")
    {
        $defaultCountry = $country;
        $data = array();
        $data['page_title'] = ucfirst($country)." | EMFI Group";
        $data['selectedMenu'] = "countries";
        $locale = session('locale');
        if (empty($locale)) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        if(!empty($country))
        {
            $main_categories =
            [
                "equities",
                "currencies",
                "commodities",
                "rates",
                "credit",
                "developed"
            ];

            if(in_array($country, $main_categories))
            {
                $type = $country;
                $market_type_developed = '';
                if($country == "developed")
                {
                    $market_type_developed = 'DEVELOPED';
                    $type = "";
                }

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
                $data['page_title'] = ucfirst($country)." | EMFI Group";
                // $data['tweets'] = getLatestTweets();
                $from = "@emfisecurities";
                $data['tweets'] = getPeopleTweets($from);

                $data['markets'] = MarketType::getArrayList();
                $arr_markets = array(  1 => 'EQUITIES',  2 => 'CURRENCIES',
                        3 => 'COMMODITIES', 4 => 'RATES', 
                        5 => 'CREDIT', 'DEVELOPED' => 'Developed');
                asort($arr_markets);
                $data['markets'] = $arr_markets;
                // $data['market_boxes'] = callCustomSP('CALL select_market()');
                $market_type_id = isset($main_categories[$type]) ? $main_categories[$type] : 1;
                $data['market_boxes'] = callCustomSP('CALL select_market_by_market_type('.$market_type_id.')');
                $data['selected_market'] = $market_type_id;
                if($market_type_developed == 'DEVELOPED'){
                    $data['selected_market'] = $market_type_developed;
                }
                $data['selected_market_text'] = $type;
                $data['last_update_date'] = getLastUpdateDate();

                if($market_type_id == 1){
                    $treeMapData = callCustomSP('CALL select_market_tree_map_data(1)');
                } else {
                    $treeMapData = callCustomSP('CALL select_market_tree_map_data(5)');
                }
                $data['treeMapData'] = $treeMapData;
                // dd($data['treeMapData']);
                // Get Tree Map Data
                // $treeMapData = callCustomSP('CALL select_analyzer_tree_map_data(0)');
                $equities = [];
                $credits = [];
                foreach ($treeMapData as $r) {
                    if ($r['market_id'] == 1) {
                        $equities['countries'][$r['country']]['title'] = $r['country_name'];
                        $equities['countries'][$r['country']]['records'][] = ['id' => $r['id'], 'security_name' => $r['security_name'], 'data' => $r];
                    } else {
                        $credits['countries'][$r['country']]['title'] = $r['country_name'];
                        $credits['countries'][$r['country']]['records'][] = ['id' => $r['id'], 'security_name' => $r['security_name'], 'data' => $r];
                    }
                }
                $data['equities'] = $equities;
                $data['credits'] = $credits;

                $default_country_id = session()->get('default_country_id');
                $continentCode      = session()->get('continentCode');
                $default_country_id = GetCountryIdFromRegion($continentCode, $default_country_id);

                if (!empty($type))
                {
                    // Get Market Pricer
                    $pricer_data = callCustomSP('CALL select_emerging_countries_security_data('.$market_type_id.')');
                    $data['pricer_data'] = $pricer_data;
                    $data['selectedMenu'] = "markets";
                    return view('market', $data);
                }
                else
                {
                    $country_id = 14;
                    if(!empty($default_country_id)){
                        $country_id = $default_country_id;
                    }
                    // Get Top 4 Box Data
                    $data['market_boxes'] = callCustomSP('CALL select_economics_country(' . $country_id . ')');

                    // Get Top Gainer
                    $gainer_data = callCustomSP('CALL select_Top_Gainer(0,1)');
                    $data['gainer_data'] = json_encode($gainer_data);

                    // Get Top Loser
                    $loser_data = callCustomSP('CALL select_Top_Loser(0,1)');
                    $data['loser_data'] = json_encode($loser_data);

                    // Get Market Pricer
                    $pricer_data = callCustomSP('CALL select_developed_countries_security_data()');
                    $data['pricer_data'] = $pricer_data;
                    $data['selectedMenu'] = "markets";

                    return view('marketDefault', $data);
                }
            }
        }
        else
        {
            $data = [];
            $data['selectedMenu'] = "countries";
            $data['last_update_date'] = getLastUpdateDate();
            $data['page_title'] = "Countries | EMFI Group";
            $data['markets'] = MarketType::getArrayList();
            $data['countries'] = Country::where("country_type", 2)->orderBy("title")->get()->toArray();
            $data['countries'] = json_encode($data['countries']);
            return view('defaultCountryPage', $data);
        }

        $data['countryObj'] = Country::where("slug", $defaultCountry)->first();

        if (!$data['countryObj']) {
            abort(404);
        }

        $data['market_boxes'] = callCustomSP('CALL select_economics_country(' . $data['countryObj']->id . ')');
        $bond_data = callCustomSP('CALL select_economic_bond(' . $data['countryObj']->id . ')');
        $data['countries'] = Country::where("country_type", 2)->orderBy("title")->get();
        $data['bond_data'] = [];
        $treeMapData = [];

        foreach ($bond_data as $r)
        {
            $treeMapData[$r['security_id']] = $r;
        }

        // echo "<pre>";print_r($treeMapData);
        // exit;

        // dd($treeMapData);

        //$treeMapData[] = $r;

        if($data['countryObj']->id == 1)
        {
            $firstTicker = "";
            foreach ($bond_data as $r)
            {
                $r['id'] = $r['security_id'];

                if(empty($firstTicker))
                {
                    $firstTicker = $r['ticker'];
                }

                $data['bond_data'][$firstTicker][] = $r;                
            }
        }
        else
        {
            foreach ($bond_data as $r)
            {
                $r['id'] = $r['security_id'];
                $data['bond_data'][$r['ticker']][] = $r;
            }
        }

        // dd($data['bond_data']);

        $data['treeMapData'] = $treeMapData;
        // dd($data['treeMapData']);
        
        if($data['countryObj']->id == 3)
        asort($data['bond_data']);        


        $data['country_benchmarkes'] = \App\Models\Tickers::getCountriesList();
        $data['number_of_charts'] = count(array_keys($data['bond_data']));
        $data['tweets'] = getSearchTweets($data['countryObj']->title);
        $data['last_update_date'] = getLastUpdateDate();

        $sql = 
        "
            SELECT * FROM securities WHERE 
            securities.`benchmark_family` = 'B10' AND 
            securities.`country_id` = 3 AND 
            securities.`market_id` = 5 LIMIT 1       
        ";

        $tmp = \DB::select($sql);
        $tmp = json_decode(json_encode($tmp),1);

        $data['default_benchmark_security_id'] = isset($tmp[0]['id']) ? $tmp[0]['id']:0;
        $data['default_benchmark_security_title'] = isset($tmp[0]['security_name']) ? $tmp[0]['security_name']:0;
        return view('economics', $data);
    }

    public function defaultCountry(Request $request)
    {
        $currentRegion = $request->segment(3);

        $data = [];
        $data['selectedMenu'] = "countries";
        $data['last_update_date'] = getLastUpdateDate();
        $data['page_title'] = "South America | EMFI Group";
        $data['markets'] = MarketType::getArrayList();
        $data['countries'] = Country::where("country_type", 2)->orderBy("title")->get()->toArray();
        $data['countries'] = json_encode($data['countries']);
        $defaultCode = "005";
        $region_id = 19;

        if($currentRegion == "caribbean")
        {
            $data['page_title'] = "The Caribbean | EMFI Group";
            $defaultCode = "029";
            $region_id = 5;
        }
        else if($currentRegion == "centralamerica")
        {
            $data['page_title'] = "Central America | EMFI Group";
            $defaultCode = "013";
            $region_id = 7;
        }
        else if($currentRegion == "northamerica")
        {
            $defaultCode = "021";
        }
        $country_list =   Country::getCountryListOfRegion($region_id);
        
        $data['country_list'] = $country_list;

        $data['defaultCode'] = $defaultCode;
        return view('defaultCountryPage', $data);
    }

    public function contact(Request $request, $type = '') {
        $data = array();
        $data['page_title'] = "Contact | EMFI Group";
        $data['type'] = $type;
        $locale = session('locale');
        if (empty($locale)) {
            $locale = 'en';
        }
        app()->setLocale($locale);

        return view('contact', $data);
    }

    public function about(Request $request) {
        $data = array();
        $data['page_title'] = "About | EMFI Group";
        $locale = session('locale');
        if (empty($locale)) {
            $locale = 'en';
        }

        app()->setLocale($locale);
        $data['teams'] = \App\Custom::getTeamData();
        return view('about', $data);
    }

    public function analyzer(Request $request, $type = '') {
        $data = array();
        $data['page_title'] = "EMFI: Analyzer";
        $data['page_type'] = $type;

        $locale = session('locale');
        if (empty($locale)) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        $data['last_update_date'] = getLastUpdateDate();
        $treeMapData = callCustomSP('CALL select_analyzer_tree_map_data(0)');
        $data['treeMap'] = [];

        $equities = [];
        $credits = [];
        foreach ($treeMapData as $r) {
            if ($r['market_id'] == 5) {
                $credits['countries'][$r['country']]['title'] = $r['country_name'];
                $credits['countries'][$r['country']]['records'][] = ['id' => $r['id'], 'security_name' => $r['security_name'], 'data' => $r];
            } else {
                $equities['countries'][$r['country']]['title'] = $r['country_name'];
                $equities['countries'][$r['country']]['records'][] = ['id' => $r['id'], 'security_name' => $r['security_name'], 'data' => $r];
            }
        }

        $data['equities'] = $equities;
        $data['credits'] = $credits;



        $default_security_id1 = 0;
        $default_security_id2 = 0;
        $treeMapData_default = callCustomSP('CALL select_analyzer_tree_map_data(1)');

        if (isset($treeMapData_default[0]) && !empty(($treeMapData_default[0]))) {
            $default_security_id1 = $treeMapData_default[0]['id'];
        }

        if (isset($treeMapData_default[1]) && !empty(($treeMapData_default[1]))) {
            $default_security_id2 = $treeMapData_default[1]['id'];
        }

        $data['default_security_id1'] = $default_security_id1;
        $data['default_security_id2'] = $default_security_id2;
        return view('analyzer', $data);
    }


    public function market(Request $request, $type = '') {
        // $j = 1;
        // $color = "#00ff00";
        // for($i = 245; $i >= 1 ; $i = $i - 12)
        // {
        //     $newColor = adjustBrightness($color, $i);
        //     echo "<br />Color: ".$newColor;
        //     echo "<br />Step: ".$i;
        //     echo '<div style="height: 100px;width: 100px;background: '.$newColor.'"></div>';
        //     echo "<br />".$j;
        //     $j++;
        // }
        // exit;
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
        $data['page_title'] = "Markets | EMFI Group";
        // $data['tweets'] = getLatestTweets();
        $from = "@emfisecurities";
        $data['tweets'] = getPeopleTweets($from);

        $data['markets'] = MarketType::getArrayList();
        // $data['market_boxes'] = callCustomSP('CALL select_market()');

        $market_type_id = isset($main_categories[$type]) ? $main_categories[$type] : 1;
        $data['market_boxes'] = callCustomSP('CALL select_market_by_market_type('.$market_type_id.')');

        $data['selected_market'] = $market_type_id;
        $data['last_update_date'] = getLastUpdateDate();

        
        // Get Tree Map Data
        $treeMapData = callCustomSP('CALL select_analyzer_tree_map_data(0)');

        $equities = [];
        $credits = [];
        foreach ($treeMapData as $r) {
            if ($r['market_id'] == 5) {
                $credits['countries'][$r['country']]['title'] = $r['country_name'];
                $credits['countries'][$r['country']]['records'][] = ['id' => $r['id'], 'security_name' => $r['security_name'], 'data' => $r];
            } else {
                $equities['countries'][$r['country']]['title'] = $r['country_name'];
                $equities['countries'][$r['country']]['records'][] = ['id' => $r['id'], 'security_name' => $r['security_name'], 'data' => $r];
            }
        }

        $data['equities'] = $equities;
        $data['credits'] = $credits;

        $default_country_id = session()->get('default_country_id');
        $continentCode      = session()->get('continentCode');
        $default_country_id = GetCountryIdFromRegion($continentCode, $default_country_id);

        if (!empty($type))
        {
            // Get Market Pricer
            $pricer_data = callCustomSP('CALL select_emerging_countries_security_data('.$market_type_id.')');
            $data['pricer_data'] = $pricer_data;
            return view('market', $data);
        }
        else
        {
            $data['selectedMenu'] = "markets";
            $country_id = 14;
            if(!empty($default_country_id)){
                $country_id = $default_country_id;
            }
            // Get Top 4 Box Data
            $data['market_boxes'] = callCustomSP('CALL select_economics_country(' . $country_id . ')');

            // Get Top Gainer
            $gainer_data = callCustomSP('CALL select_Top_Gainer(0,1)');
            $data['gainer_data'] = json_encode($gainer_data);

            // Get Top Loser
            $loser_data = callCustomSP('CALL select_Top_Loser(0,1)');
            $data['loser_data'] = json_encode($loser_data);

            // Get Market Pricer
            $pricer_data = callCustomSP('CALL select_developed_countries_security_data()');
            $data['pricer_data'] = $pricer_data;

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
        $data['page_title'] = "Terms Of Use | EMFI Group";
        app()->setLocale($locale);
        $content = \App\Models\CmsPage::where('page_constant', $pageID)->first();
        if (!$content) {
            return abort(404);
        }
        $data['content'] = $content;
        return view('terms_of_uses', $data);
    }
    public function scam_alert() {
        $locale = session('locale');
        if (empty($locale)) {
            $locale = 'en';
        }
        $pageID = "SCAM_ALERT";
        $data = array();
        $data['page_title'] = "Scam Alert | EMFI Group";
        app()->setLocale($locale);
        $content = \App\Models\CmsPage::where('page_constant', $pageID)->first();
        if (!$content) {
            return abort(404);
        }
        $data['content'] = $content;
        return view('scam_alert', $data);
    }
    public function privacy_statements() {
        $locale = session('locale');
        if (empty($locale)) {
            $locale = 'en';
        }
        $pageID = "PRIVACY_STATEMENTS";
        $data = array();
        $data['page_title'] = "Privacy Policy | EMFI Group";
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
        $data['page_title'] = "Cookies Policy | EMFI Group";

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
        if(isset($languages[$locale]) && !empty($languages[$locale]))
        {
            session(['locale' => $locale]);
            $url = \URL::previous();
            $tmp = explode('/', $url);

            if(count($tmp) == 4 && empty($tmp[3]))
            {
                return redirect(getLangName());
            }

            if(getLangName() == "espanol")
            $url = str_replace('english',getLangName(), $url);
            else
            $url = str_replace('espanol',getLangName(), $url);

            return redirect($url);
        }
        else
        {
            return redirect('/');
        }
    }

    public function GetMarketHistoryData($slider) {

        $benchmark_id = $slider->option_banchmark;
        $security_id = $slider->security_id;
        $month_id = $slider->option_period;
        $price_id = $slider->option_prices;

        $chart_data['options']['option_banchmark'] = $slider->option_banchmark;
        $chart_data['options']['security_id'] = $slider->security_id;
        $chart_data['options']['option_period'] = $slider->option_period;
        $chart_data['options']['option_prices'] = $slider->option_prices;

        $SP_data = "CALL select_security_historical_data(" . $security_id . ", " . $month_id . ")";
        $history_data = callCustomSP($SP_data);
        // prd($history_data);
        $chart_data['history_data'] = $history_data;
        $chart_data['benchmark_history_data'] = [];
        if ($benchmark_id > 0) {
            $history_data = "CALL select_security_historical_data(" . $benchmark_id . ", " . $month_id . ")";
            $data = callCustomSP($history_data);
            $benchmark_history_data = $data;
            if (!empty($benchmark_history_data)) {
                $dataKeys = [];
                foreach ($chart_data['history_data'] as $row) {
                    if ($price_id == 2) {
                        $dataKeys[$row['created']]['column1'] = $row['YLD_YTM_MID'];
                    } else if ($price_id == 3) {
                        $dataKeys[$row['created']]['column1'] = $row['Z_SPRD_MID'];
                    } else {
                        $dataKeys[$row['created']]['column1'] = $row['last_price'];
                    }
                    $dataKeys[$row['created']]['column2'] = NULL;
                    $dataKeys[$row['created']]['date'] = $row['created_format'];
                    $dataKeys[$row['created']]['main_date'] = $row['created'];
                    $chart_data['options']['title'] = $row['title'];
                }
                foreach ($benchmark_history_data as $row) {
                    if ($price_id == 2) {
                        $dataKeys[$row['created']]['column2'] = $row['YLD_YTM_MID'];
                    } else if ($price_id == 3) {
                        $dataKeys[$row['created']]['column2'] = $row['Z_SPRD_MID'];
                    } else {
                        $dataKeys[$row['created']]['column2'] = $row['last_price'];
                    }
                    if (!isset($dataKeys[$row['created']]['column1'])){
                        $dataKeys[$row['created']]['column1'] = NULL;
                    }

                    $dataKeys[$row['created']]['date'] = $row['created_format'];
                    $dataKeys[$row['created']]['main_date'] = $row['created'];
                    $chart_data['options']['title2'] = $row['title'];
                }

                ksort($dataKeys);
                $i = 0;
                foreach ($dataKeys as $key => $val) {
                    if (!empty($dataKeys[$key]['date'])) {
                        $finalData[$i] = [$dataKeys[$key]['date'], $dataKeys[$key]['column1'], $dataKeys[$key]['column2'], $dataKeys[$key]['main_date']];
                        $i++;
                    }
                }
                $chart_data['benchmark_history_data'] = $finalData;
            }
        }
        return $chart_data;
    }

    public function GetYieldCurveChartData($slider) {
        $country = $slider->country_id;
        $month_id = $slider->option_period;
        $benchmark_id = $slider->option_banchmark;
        $price_id = $slider->option_prices;
        $duration = $slider->option_maturity;
        $tickerType = 1;
        $tid = 1;
        $data['options']['option_period'] = $slider->option_period;
        $data['options']['option_banchmark'] = $slider->option_banchmark;
        $data['options']['option_prices'] = $slider->option_prices;
        $data['options']['option_maturity'] = $slider->option_maturity;

        $month_id = date("Y-m-d", strtotime("-" . $month_id . " months"));
        // $month_id = '2017-04-07';
        $history_data = "CALL select_counrty_yield_curve_bond_data(" . $country . ", '" . $month_id . "'," . $tickerType . ")";
        $history_data = callCustomSP($history_data);
        $rows = [];
        $i = 0;
        if (!empty($history_data)) {
            foreach ($history_data as $row) {
                $price = $row['last_price'];
                $category = $row['dur_adj_mid'];
                $extraTitle = $row['security_name'];
                if ($price_id == 2) {
                    $price = $row[strtolower('YLD_YTM_MID')];
                } else if ($price_id == 3) {
                    $price = $row[strtolower('Z_SPRD_MID')];
                }

                if ($duration == 1) {
                    $category = $row['maturity_date'];
                    $date = new \DateTime($row['maturity_date']);
                    $category = $date->format("d M, Y");
                } else if ($duration == 2) {
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
        if ($benchmark_id > 0) {
            $history_data = "CALL select_counrty_yield_curve_bond_data(" . $benchmark_id . ", '" . $month_id . "'," . $tid . ")";
            $dataTemp = callCustomSP($history_data);
            $benchmark_history_data = $dataTemp;
            if (true) {
                $dataKeys = [];
                $i = 0;
                foreach ($data['history_data'] as $row) {
                    $dataKeys[$i]['title1'] = $row['category'];
                    $dataKeys[$i]['date_difference'] = $row['date_difference'];
                    $dataKeys[$i]['price1'] = $row['price'];
                    $dataKeys[$i]['tooltip'] = $row['tooltip'];
                    $dataKeys[$i]['title2'] = "";
                    $dataKeys[$i]['price2'] = NULL;
                    $i++;
                }
                $i = 0;
                foreach ($benchmark_history_data as $row) {
                    $price = $row['last_price'];
                    $category = $row['dur_adj_mid'];
                    $extraTitle = date("d M, Y", strtotime($row['maturity_date']));

                    if ($price_id == 2) {
                        $price = $row[strtolower('YLD_YTM_MID')];
                    } else if ($price_id == 3) {
                        $price = $row[strtolower('Z_SPRD_MID')];
                    }

                    if ($duration == 1) {
                        $category = $row['maturity_date'];
                        $date = new \DateTime($row['maturity_date']);
                        $category = $date->format("d M, Y");
                    } else if ($duration == 2) {
                        $category = $row['dur_adj_mid'];
                    }
                    $dataKeys[$i]['title2'] = $category;
                    $dataKeys[$i]['price2'] = $price;
                    $dataKeys[$i]['tooltip2'] = $row['security_name'];

                    if (!isset($dataKeys[$i]['title1'])) {
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

    public function GetDifferentialChartData($slider) {

        $security_id = $slider->security_id;
        $option_security = $slider->option_security;
        $option_prices = $slider->option_prices;
        $option_period = $slider->option_period;

        $security1 = \App\Models\Securities::find($security_id);
        $security2 = \App\Models\Securities::find($option_security);

        $isEquity = 0;
        if ($security1->market_id != 5 || $security2->market_id != 5) {
            $isEquity = 1;
        }
        if ($isEquity == 1 && $option_prices == 3) {
            $option_prices = 1;
        }

        $data['options']['security_id'] = $slider->security_id;
        $data['options']['option_security'] = $slider->option_security;
        $data['options']['option_prices'] = $slider->option_prices;
        $data['options']['option_period'] = $slider->option_period;
        $SP_call = 'CALL select_analyzer_bond_data(' . $security_id . ',' . $option_security . ',' . $option_period . ')';
        $area_chart = callCustomSP($SP_call);
        if (!empty($area_chart)) {
            foreach ($area_chart as $key => $val) {
                $area_chart[$key]['created_format'] = date("d M Y", strtotime($area_chart[$key]['created']));

                if ($option_prices == 1) {
                    $area_chart[$key]['main_price'] = $area_chart[$key]['price_difference'];
                }
                if ($option_prices == 2) {
                    $area_chart[$key]['main_price'] = $area_chart[$key]['YLD_difference'];
                }
                if ($option_prices == 3) {
                    $area_chart[$key]['main_price'] = $area_chart[$key]['Z_difference'];
                }
            }
        }
        $data['area_chart'] = $area_chart;
        $chart_data = $data;

        return $chart_data;
    }

    public function GetRegressionChartData($slider) {
        $security_id = $slider->security_id;
        $option_security = $slider->option_security;
        $option_prices = $slider->option_prices;
        $option_period = $slider->option_period;

        $security1 = \App\Models\Securities::find($security_id);
        $security2 = \App\Models\Securities::find($option_security);

        $isEquity = 0;
        if ($security1->market_id != 5 || $security2->market_id != 5) {
            $isEquity = 1;
        }
        if ($isEquity == 1 && $option_prices == 3) {
            $option_prices = 1;
        }

        $data['options']['security_id'] = $slider->security_id;
        $data['options']['option_security'] = $slider->option_security;
        $data['options']['option_prices'] = $slider->option_prices;
        $data['options']['option_period'] = $slider->option_period;

        $SP_Call = 'CALL select_analyzer_bond_data(' . $security_id . ',' . $option_security . ',' . $option_period . ')';
        $regression_chart = callCustomSP($SP_Call);
        if (!empty($regression_chart)) {
            $i = 1;
            $last_count = count($regression_chart);
            foreach ($regression_chart as $key => $val) {
                $regression_chart[$key]['created_format'] = date("d M Y", strtotime($regression_chart[$key]['created']));

                if ($option_prices == 1) {
                    $regression_chart[$key]['main_price'] = $regression_chart[$key]['last_price'];
                    $regression_chart[$key]['main_price2'] = $regression_chart[$key]['last_price2'];
                } else if ($option_prices == 2) {
                    $regression_chart[$key]['main_price'] = $regression_chart[$key]['YLD_YTM_MID'];
                    $regression_chart[$key]['main_price2'] = $regression_chart[$key]['YLD_YTM_MID2'];
                } else if ($option_prices == 3) {
                    $regression_chart[$key]['main_price'] = $regression_chart[$key]['Z_SPRD_MID'];
                    $regression_chart[$key]['main_price2'] = $regression_chart[$key]['Z_SPRD_MID2'];
                }

                if ($i == $last_count)
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

    public function GetRelativeValueChartData($slider) {
        $country_id = $slider->country_id;
        $option_period = $slider->option_period;
        $option_prices = $slider->option_prices;
        $option_rating = $slider->option_rating;
        $option_credit = $slider->option_credit;

        $data['options']['country_id'] = $slider->country_id;
        $data['options']['option_period'] = $slider->option_period;
        $data['options']['option_prices'] = $slider->option_prices;
        $data['options']['option_rating'] = $slider->option_rating;
        $data['options']['option_credit'] = $slider->option_credit;

        $relvalMonth = date("Y-m-d", strtotime("-" . $option_period . " months"));
        // $relvalMonth = "2018-04-16";
        $query = 'CALL select_relval_chart_data(' . $option_credit . ',"' . $relvalMonth . '",0 )';

        $relval_chart = callCustomSP($query);

        if ($relvalMonth == date("Y-m-d")) {
            if (empty($relval_chart)) {
                $relvalMonth = \App\Models\Securities::max("created");
                $relval_chart = callCustomSP('CALL select_relval_chart_data(' . $option_credit . ',"' . $relvalMonth . '",0)');
            }
        }

        $data1 = [];
        $finalArray = [];
        if (!empty($relval_chart)) {
            $i = 0;
            foreach ($relval_chart as $r) {
                if ($option_rating == 1) {
                    $data1[$i]['category'] = $r['rtg_sp'];
                } else {
                    $data1[$i]['category'] = $r['current_oecd_member_cor_class'];
                }

                if ($option_prices == 1) {
                    $data1[$i]['price'] = $r['last_price'];
                } else if ($option_prices == 2) {
                    $data1[$i]['price'] = $r['YLD_YTM_MID'];
                } else if ($option_prices == 3) {
                    $data1[$i]['price'] = $r['Z_SPRD_MID'];
                }

                $data1[$i]['country_title'] = $r['country_title'];
                $data1[$i]['security_name'] = $r['security_name'];
                $data1[$i]['created_format'] = $r['created_format'];
                $data1[$i]['country_code'] = $r['country_code'];
                $i++;
            }
            $i = 0;
            foreach ($data1 as $r) {
                $finalArray[$r['category']][] = ['price' => $r['price'], 'country_title' => $r['country_title'],'country_code' => $r['country_code']];
            }

            /*if ($option_rating == 1) {
                ksort($finalArray);
            } else {
                krsort($finalArray);
            }*/
        }
        $data['relative_data'] = $finalArray;
        $chart_data = $data;
        return $chart_data;
    }

    public function contact_form_data(Request $request) {
        $status = 1;
        $msg = 'Your message has been sent!';
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required|min:2',
                    'last_name' => 'required|min:2',
                    'organization' => 'required',
                    'country' => 'required|min:2',
                    'phone' => 'required|numeric',
                    'email' => 'required|email',
                    'business_unit' => 'required',
                    'subject' => 'required',
                    'message' => 'required|min:5',
        ]);

        // check validations
        if ($validator->fails()) {
            $messages = $validator->messages();

            $status = 0;
            $msg = "";

            foreach ($messages->all() as $message) {
                $msg .= $message . "<br />";
            }
        } else {
            $first_name = $request->get('first_name');
            $last_name = $request->get('last_name');
            $country = $request->get('country');
            $organization = $request->get('organization');
            $email = $request->get('email');
            $phone = $request->get('phone');
            $subject = $request->get('subject');
            $message = $request->get('message');
            $busineess_unit = $request->get('business_unit');

            if ($request->hasFile("attachment")) {

                $today = date('d-m-y');
                // $imageName = $request->attachment->getClientOriginalName();
                // $imageName = str_re.place('.' . $request->attachment->getClientOriginalExtension(), '', $imageName);
                $imageName = UploadFileRename($today).$request->file('attachment')->getClientOriginalName();

                // $uploadPath = 'uploads' . DIRECTORY_SEPARATOR . 'instruments' . DIRECTORY_SEPARATOR . $id;
                $uploadPath = 'uploads'.DIRECTORY_SEPARATOR.'contact_us_files'.DIRECTORY_SEPARATOR.$today;

                $request->attachment->move($uploadPath, $imageName);
                $fileurl = $uploadPath.DIRECTORY_SEPARATOR.$imageName;
            }

            $toEmail = "";
            if($busineess_unit == 1) 
            {
                $busineess_unit_name = "Asset Management";
                $toEmail = "contact@emficapital.com";
            }
            elseif ($busineess_unit == 2) {
                $toEmail = "contact@emfiwealth.com";
                $busineess_unit_name = "Wealth Management";
            }
            elseif ($busineess_unit == 3) {
                $toEmail = "contact@emfisecurities.com";
                $busineess_unit_name = "Capital Markets";

            }
            elseif ($busineess_unit == 4) {
                $toEmail = "contact@emfiprime.com";
                $busineess_unit_name = "Prime Brokerage";
            }
            elseif ($busineess_unit == 5) {
                $toEmail = "contact@emfianalytics.com";
                $busineess_unit_name = "Data Analytics";
            }

            $html = '<p> Hi,</p>';
            $html .= '<p><b>Name : </b>' . $first_name .' '.$last_name.'</p>';
            $html .= '<p><b>Organization name : </b>' . $organization . '</p>';
            $html .= '<p><b>Country name : </b>' . $country . '</p>';            
            $html .= '<p><b>Phone : </b>' . $phone . '</p>';
            $html .= '<p><b>Email : </b>' . $email . '</p>';            
            $html .= '<p><b>Business Unit : </b>' . $busineess_unit_name . '</p>';
            $html .= '<p><b>Subject : </b>' . $subject . '</p>';
            // $html .= '<p><b>toEmail : </b>' . $toEmail . '</p>';
            $html .= '<p><b>Message</b></p>';
            $html .= '<p>' . $message . '</p>';
            $html .= '<p>' . ucfirst($first_name) . ' ' . ucfirst($last_name) . '</p>';
            if (!empty($fileurl)) {
                $html .= '<p><a href="'.url($fileurl).'" class="btn btn-default" download>Download Attachment</a></p>';
            }
            $html .= '<p>Thank you !</p>';


            // $params["to"] = $toEmail;
            // $params["to"] = 'ashoksadhu16@gmail.com';
            $params["to"] = 'contact@emfi.eu';
            
            $params["from"] = $email;
            $params["subject"] = "EMFI: Contact Details";
            $params["body"] = $html;


            sendHtmlMail($params);
        }
        return ['status' => $status, 'msg' => $msg];
    }

    public function about_form_data(Request $request) 
    {
        // dd($request);
        $status = 1;
        $msg = 'Email has been sent successfully !';
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required|min:2',
                    'last_name' => 'required|min:2',
                    'country' => 'required|min:2',
                    'email' => 'required|email',
                    'subject' => 'required|min:2',
                    'message' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();

            $status = 0;
            $msg = "";

            foreach ($messages->all() as $message) {
                $msg .= $message . "<br />";
            }
        } else {
            $first_name = $request->get('first_name');
            $last_name = $request->get('last_name');
            $country = $request->get('country');
            $email = $request->get('email');
            $subject = $request->get('subject');
            $message = $request->get('message');

            $html = '<p> Hi,</p>';
            $html .= '<p><b>Name : </b>' . $first_name .' '.$last_name.'</p>';
            $html .= '<p><b>Country name : </b>' . $country . '</p>';
            $html .= '<p><b>Email : </b>' . $email . '</p>';
            $html .= '<p><b>Subject : </b>' . $subject . '</p>';
            $html .= '<p><b>Message</b></p>';
            $html .= '<p>' . $message . '</p>';
            $html .= '<br><p>' . ucfirst($first_name) . ' ' . ucfirst($last_name) . '</p>';
            $html .= '<p>Thank you !</p>';

            $params["to"] = 'reports.phpdots@gmail.com';
            $params["to"] = 'rathodakshay228@gmail.com';
            $params["from"] = $email;
            $params["subject"] = "EMFI: Contact Details";
            $params["body"] = $html;

            // dd($params);

            sendHtmlMail($params);
        }
        return ['status' => $status, 'msg' => $msg];        
    }

    public function services(Request $request)
    {
        $type = \Request::segment(2);
        $data = [];

        if ($type == 'capital') {
            $data['page_title'] = 'Asset Management | EMFI Group';
            $data['page_title_name'] = 'Asset Management';
            $view = 'services.asset_management';
        }
        elseif ($type == 'wealth') {
            $data['page_title'] = "Wealth Management | EMFI Group";
            $data['page_title_name'] = "Wealth Management";
            $view = 'services.wealth_management';
        }
        elseif ($type == 'securities') {
            $data['page_title'] = 'Capital Markets | EMFI Group';
            $data['page_title_name'] = 'Capital Markets';
            $view = 'services.investment_banking';
        }
        elseif ($type == 'prime') {
            $data['page_title'] = 'Prime Brokerage | EMFI Group';
            $data['page_title_name'] = 'Prime Brokerage';
            $view = 'services.prime_brokerage';
        }
        elseif ($type == 'analytics') {
            $data['page_title'] = 'Data Analytics | EMFI Group';
            $data['page_title_name'] = 'Data Analytics';
            $view = 'services.data_analytics';
        }

        //$view = 'services.asset_management';

        $locale = session('locale');
        if (empty($locale))
        {
            $locale = 'en';
        }
        app()->setLocale($locale);
        $data['selectedMenu'] = "services";

        return view($view , $data);
    }

}
