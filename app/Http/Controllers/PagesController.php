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


class PagesController extends Controller {

    public function __construct() {

    }

    public function home(Request $request) 
    {
        $data = array();
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
            // $defaultCountry = 40;
        }
        
        $data['countryObj'] = Country::where("country_code",$defaultCountry)->first();
        // $data['countryObj'] = Country::where("id",$defaultCountry)->first();
        
        if(!$data['countryObj'])
        {
            abort(404);
        }        

        $data['market_boxes'] = callCustomSP('CALL Select_economics_country('.$data['countryObj']->id.')');
        $data['bond_data'] = callCustomSP('CALL select_economic_bond('.$data['countryObj']->id.')');
        $data['countries'] = Country::orderBy("title")->get();
        
        $data['tweets'] = getSearchTweets($data['countryObj']->title);        
        // dd($data['tweets']);
        // $data['tweets'] = getSearchTweets($data['countryObj']->title);
        // dd($data['bond_data']);
        return view('economics', $data);
    }

    public function contact(Request $request) {
        $data = array();
        $data['page_title'] = "EMFI: Contact";
        return view('contact', $data);
    }

    public function about(Request $request) {
        $data = array();
        $data['page_title'] = "EMFI: About";
        return view('about', $data);
    }

    public function analyzer(Request $request) {
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
        $data['tweets'] = getPeopleTweets($from);        

        $data['markets'] = MarketType::getArrayList();
        $data['market_boxes'] = callCustomSP('CALL select_market()');
        $data['selected_market'] = isset($main_categories[$type]) ? $main_categories[$type]:1;
        return view('market', $data);
    }

}
