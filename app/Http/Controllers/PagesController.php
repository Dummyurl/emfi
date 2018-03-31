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

    public function home(Request $request)
    {
		$data = array();
        $data['page_title'] = "EMFI: Home";
        $data['sliders'] = HomeSlider::getHomeSliders(2);
        $locale = session('locale');
        if(empty($locale))
        {
            $locale = 'en';
        }
        app()->setLocale($locale);
        return view('welcome', $data);
    }

    public function economics(Request $request, $country = "")
    {
        $data = array();
        $data['page_title'] = "EMFI: Economics";

        if(!empty($country))
        {
            $defaultCountry = $country;
        }
        else
        {
            $defaultCountry = "VZ";
        }

        $data['countryObj'] = Country::where("country_code",$defaultCountry)->first();

        if(!$data['countryObj'])
        {
            abort(404);
        }

        $data['market_boxes'] = callCustomSP('CALL Select_economics_country('.$data['countryObj']->id.')');
        $bond_data = callCustomSP('CALL select_economic_bond('.$data['countryObj']->id.')');
        $data['countries'] = Country::orderBy("title")->get();
        $data['bond_data'] = [];

        foreach($bond_data as $r)
        {
            $data['bond_data'][$r['ticker']][] = $r;
        }

        $data['country_benchmarkes'] = \App\Models\Tickers::getCountriesList();
        // dd($data['country_benchmarkes']);        
        $data['number_of_charts'] = count(array_keys($data['bond_data']));        
        $data['tweets'] = getSearchTweets($data['countryObj']->title);
        $data['last_update_date'] = getLastUpdateDate();        
        return view('economics', $data);
    }

    public function contact(Request $request)
    {
        $data = array();
        $data['page_title'] = "EMFI: Contact";
        $locale = session('locale');
        if(empty($locale))
        {
            $locale = 'en';
        }
        app()->setLocale($locale);
        
        return view('contact', $data);
    }

    public function about(Request $request) {
        $data = array();
        $data['page_title'] = "EMFI: About";
        $locale = session('locale');
        if(empty($locale))
        {
            $locale = 'en';
        }

        app()->setLocale($locale);
        return view('about', $data);
    }

    public function analyzer(Request $request)
    {
        $data = array();
        $data['page_title'] = "EMFI: Analyzer";
        return view('analyzer', $data);
    }

    public function market(Request $request, $type = '')
    {
        $main_categories = [
          "equities" => 1,
          "currencies" => 2,
          "commodities" => 3,
          "rates" => 4,
          "credit" => 5,
        ];

        $data = array();
        $data['page_title'] = "EMFI: Markets";
        // $data['tweets'] = getLatestTweets();
        $from = "@emfisecurities";
        $data['tweets'] = [];//getPeopleTweets($from);

        $data['markets'] = MarketType::getArrayList();
        $data['market_boxes'] = callCustomSP('CALL select_market()');
        $data['selected_market'] = isset($main_categories[$type]) ? $main_categories[$type]:1;
        
        // dd($data['market_boxes']);

        $data['last_update_date'] = getLastUpdateDate();        
        return view('market', $data);
    }
    public function terms_of_uses()
    {
        $locale = session('locale');
        if(empty($locale))
        {
            $locale = 'en';
        }
        $pageID = "TERMS_OF_USES";
        $data = array();
        $data['page_title'] = "EMFI: Terms Of Uses";
        app()->setLocale($locale);
        $content = \App\Models\CmsPage::where('page_constant',$pageID)->first();
        if(!$content)
        {
            return abort(404);
        }
        $data['content'] = $content;
        return view('terms_of_uses', $data);
    }

    public function privacy_statements()
    {
        $locale = session('locale');
        if(empty($locale))
        {
            $locale = 'en';
        }
        $pageID = "PRIVACY_STATEMENTS";
        $data = array();
        $data['page_title'] = "EMFI: Privacy Statements";
        app()->setLocale($locale);
        $content = \App\Models\CmsPage::where('page_constant',$pageID)->first();
        if(!$content)
        {
            return abort(404);
        }
        $data['content'] = $content;
        return view('privacy_statments', $data);
    }
    
    public function cookies()
    {
        $locale = session('locale');
        if(empty($locale))
        {
            $locale = 'en';
        }
        $pageID = "COOKIES";
        $data = array();
        $data['page_title'] = "EMFI: Cookies";

        app()->setLocale($locale);

        $content = \App\Models\CmsPage::where('page_constant',$pageID)->first();
        if(!$content)
        {
            return abort(404);
        }
        $data['content'] = $content;
        return view('cookies', $data);
    }
    
    public function change_locale($locale)
    {
        $languages = \App\Custom::getLanguages();
        if(isset($languages[$locale]) && !empty($languages[$locale]))
        {
            session(['locale' => $locale]);
            return redirect()->back();
        }
        else{
            return redirect('/');
        }        
    }

}
