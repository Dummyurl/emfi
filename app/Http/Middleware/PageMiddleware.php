<?php

namespace App\Http\Middleware;
use Cookie;
use Closure;
use Illuminate\Support\Facades\Auth;

class PageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $client_ip =  "186.227.194.138"; //brazil
        $client_ip =  "106.201.237.251"; //India
        $client_ip =  "169.57.123.212"; //Maxico
        $client_ip =  "165.72.200.11"; //Europe
        $client_ip =  "89.191.212.57"; //UK
        $client_ip =  "196.43.34.190"; //Africa
        // $client_ip =  GetUserIp();
        $Name = session()->get('country_name');
        if(!$Name)
        {
            $CountryDetails = GetCountryDetailFromIp($client_ip);
            $CountryName = $CountryDetails->geoplugin_countryName;
            $geoplugin_continentCode = $CountryDetails->geoplugin_continentCode;
            $url = $this->GetCountryURL($CountryName);
            session()->put('country_name', $CountryName);
            session()->put('continentCode', $geoplugin_continentCode);
            return redirect($url);
        }
        return $next($request);
    }

    public function GetCountryURL($CountryName=null){
        if(!empty($CountryName)){
            $countries = \App\Models\Country::where('title',strtoupper($CountryName))->first();
            if(!empty($countries)){
                session()->put('default_country_id', $countries->id);
                return $countries->country_url;
            }
            return "";
        }
    }
}
