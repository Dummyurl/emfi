@if(session("is_close_disclaimer") != 1)
    <div class="disclaimer_block">
        <div class="row disclaimer ftr_txt">
            <!-- <div class="col-md-12">
                <span title="Close" class="pull-right close_disclaimer" style="color:white;cursor: pointer;">X</span>
            </div> -->
            <div class="container">
                <!-- <h4>//{{ __('footer.disclaimer') }}</h4> -->
                <p>{{ __('footer.disclaimer_desc_1') }} <a href="{{ url(getLangName().'/cookies-policy')  }}">Cookies Policy</a>. {{ __('footer.disclaimer_desc_2') }} <a href="{{ url(getLangName().'/privacy-policy') }}">Privacy Policy</a>, <a href="{{ url(getLangName().'/terms-of-use') }}">Terms of Uses</a> and <a href="{{ url(getLangName().'/scam-alert') }}">Scam Alert</a> {{ __('footer.disclaimer_desc_3') }}  
                </p>
                <div class="row">
                    <a href="#" title="Close" class="pull-right close_disclaimer"> OK </a>
                </div>
            </div>
        </div>
    </div>
@endif
<div class="nav_wrapper">
    <nav class="navbar navbar-default navbar-static-top" id="myNavigation">
        <div class="container">
            <ul class="rightlinks">
                <li class="dropdown">
                    <a title="Change Language" 
                        onclick="window.location='{{ session('locale') == "es" ? url('change-language/en'):url('change-language/es')}}';" 
                        href="{{ session('locale') == "es" ? url('change-language/en'):url('change-language/es')}}" 
                        class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        
                        @if(session('locale') == "es")
                            ESPAÑOL
                        @else
                            ENGLISH
                        @endif
                    </a>
                    <ul class="dropdown-menu">
                        
                        @if(session('locale') == "es")
                            <li><a title="Change Language" href="{{ url('change-language/en') }}">ENGLISH</a></li>
                        @else
                            <li><a title="Change Language" href="{{ url('change-language/es') }}">ESPAÑOL</a></li>
                        @endif
                    </ul>
                </li>
                
                <li> 
                        <a href="{{ \Config('app.login_link')}}" class="login_btn">
                            {{ __('header.login') }}                        
                        </a>
                </li>
            </ul>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></button>
                <a class="navbar-brand white" href="{{ url(getLangName()) }}">
                    <img src="{{ asset('themes/emfi/images/emfi-logo.png') }}" />
                </a> 
                <a class="navbar-brand dark" href="{{ url(getLangName()) }}">
                    <img src="{{ asset('themes/emfi/images/emfi-logo-dark.png') }}" alt="EMFI Securities" />
                </a> 
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="{{ \Request::is('/','home','english','espanol') ? 'active':'' }}">
                        <a href="{{ url(getLangName()) }}">{{ __('header.home') }}</a>
                    </li>
                    <li class="{{ (isset($selectedMenu) && $selectedMenu == 'markets') ? 'active':'' }} dropdown">
                        <a href="{{ url(getLangName().'/credit') }}"> {{ __('header.markets') }}</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url(getLangName().'/credit') }}">{{ __('header.credit') }}</a></li>
                            <li><a href="{{ url(getLangName().'/rates') }}">{{ __('header.rates') }}</a></li>
                            <li><a href="{{ url(getLangName().'/currencies') }}">{{ __('header.currencies') }}</a></li>
                            <li><a href="{{ url(getLangName().'/commodities') }}">{{ __('header.commodities') }}</a></li>
                            <li><a href="{{ url(getLangName().'/equities') }}">{{ __('header.equities') }}</a></li>
                        </ul>
                    </li>
                    <li class="{{ (isset($selectedMenu) && $selectedMenu == 'countries') ? 'active':'' }} dropdown">
                        <a href="{{ url(getLangName().'/countries/southamerica')}}">{{ __('header.economics') }}</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url(getLangName().'/countries/southamerica')}}">{{ __('header.south_america') }}</a></li>
                            <li><a href="{{ url(getLangName().'/countries/caribbean')}}">{{ __('header.caribbean') }}</a></li>
                            <li><a href="{{ url(getLangName().'/countries/centralamerica')}}">{{ __('header.central_america') }}</a></li>
                        </ul>
                    </li>
                    <li class="{{ (isset($selectedMenu) && $selectedMenu == 'services') ? 'active':'' }}  dropdown">
                        <a href="{{ url(getLangName().'/capital')}}">{{ __('header.service') }}</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url(getLangName().'/capital')}}">{{ __('header.asset_management') }}</a></li>
                            <li><a href="{{ url(getLangName().'/wealth')}}">{{ __('header.wealth_management') }}</a></li>
                            <li><a href="{{ url(getLangName().'/securities')}}">{{ __('header.investment_banking') }}</a></li>
                            <li><a href="{{ url(getLangName().'/prime')}}">{{ __('header.prime_brokerage') }}</a></li>
                            <!-- <li><a href="{{ url(getLangName().'/analytics')}}">{{ __('header.data_analytics') }}</a></li> -->
                        </ul>
                    </li>
                    <li class="{{ \Request::is(getLangName().'/about') ? 'active':'' }} dropdown">
                        <a href="{{ url(getLangName().'/about')}}">{{ __('header.about') }}</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url(getLangName().'/about')}}#why_emfi">{{ __('header.why_emfi') }}</a></li>
                            <li><a href="{{ url(getLangName().'/about')}}#team">{{ __('header.team') }}</a></li>
                            <li><a href="{{ url(getLangName().'/about')}}#clients">{{ __('header.clients') }}</a></li>
                            <li><a href="{{ url(getLangName().'/about')}}#candidates">{{ __('header.candidates') }}</a></li>
                            <li><a href="{{ url(getLangName().'/about')}}#presence">{{ __('header.presence') }}</a></li>
                        </ul>
                    </li>
                    <li class="{{ \Request::is(getLangName().'/contact',getLangName().'/contact/*') ? 'active':'' }} dropdown">
                        <a href="{{ url(getLangName().'/contact')}}">{{ __('header.contact') }}</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url(getLangName().'/contact/enquiry')}}">{{ __('header.enquiry') }}</a></li>
                            <li><a href="{{ url(getLangName().'/contact/feedback')}}">{{ __('header.feedback') }}</a></li>
                            <li><a href="{{ url(getLangName().'/contact/complaint')}}">{{ __('header.complaint') }}</a></li>
                            <li><a href="{{ url(getLangName().'/contact/careers')}}">{{ __('header.careers') }}</a></li>
                            <li><a href="{{ url(getLangName().'/contact/locations')}}#locations">{{ __('header.locations') }}</a></li>
                        </ul>
                    </li>                    
                </ul>
            </div>
        </div>
    </nav>
</div>