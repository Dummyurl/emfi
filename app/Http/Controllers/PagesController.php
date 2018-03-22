<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \App\Models\MarketType;
use Validator;

class PagesController extends Controller {

    public function __construct() {
        
    }

    public function home(Request $request) {
        $data = array();
        return view('welcome', $data);
    }

    public function economics(Request $request) {
        $data = array();
        $data['page_title'] = "EMFI: Economics";
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
        $data['page_title'] = "EMFI: Market";
        $data['tweets'] = getLatestTweets();
        
        // dd($data['tweets']);

        $data['markets'] = MarketType::getArrayList();
        $data['market_boxes'] = callCustomSP('CALL select_market()');
        $data['selected_market'] = isset($main_categories[$type]) ? $main_categories[$type]:1;        
        return view('market', $data);
    }

}
