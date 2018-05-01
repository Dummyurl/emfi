<div class="nav_wrapper{{ \Request::is('economics') ? ' custom-bg-header':'' }}">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <ul class="rightlinks">
                <?php /*
                <li class="dropdown"> 
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        {{ __('header.login') }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#">{{ __('header.portfolio') }}</a></li>
                        <li><a href="#">{{ __('header.research') }}</a></li>
                    </ul>
                </li>
                */ ?>
                <li class="dropdown"> 
                    <a title="Change Language" onclick="window.location='{{ session('locale') == "es" ? url('change-language/en'):url('change-language/es')}}';" href="{{ session('locale') == "es" ? url('change-language/en'):url('change-language/es')}}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
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
            </ul>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">{{ __('header.toggle') }}</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></button>
                <a class="navbar-brand white" href="{{ url(getLangName()) }}">
                    <img src="{{ asset('themes/frontend/images/emfi-logo.png') }}" alt="EMFI Securities">
                </a> 
                <a class="navbar-brand dark" href="{{ url(getLangName()) }}">
                    <img src="{{ asset('themes/frontend/images/emfi-logo-dark.png') }}" alt="EMFI Securities">
                </a> 
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="{{ \Request::is('/','home','english','espanol') ? 'active':'' }}">
                        <a href="{{ url(getLangName()) }}">{{ __('header.home') }}</a>
                    </li>
                    <li class="{{ \Request::is(getLangName().'/markets',getLangName().'/markets/*') ? 'active':'' }} dropdown">
                        <a href="{{ url(getLangName().'/developed') }}"> {{ __('header.markets') }}</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url(getLangName().'/credit') }}">{{ __('header.credit') }}</a></li>
                            <li><a href="{{ url(getLangName().'/rates') }}">{{ __('header.rates') }}</a></li>
                            <li><a href="{{ url(getLangName().'/currencies') }}">{{ __('header.currencies') }}</a></li>
                            <li><a href="{{ url(getLangName().'/commodities') }}">{{ __('header.commodities') }}</a></li>
                            <li><a href="{{ url(getLangName().'/equities') }}">{{ __('header.equities') }}</a></li>
                        </ul>
                    </li>
                    <?php 
                    /*
                    <li class="dropdown {{ \Request::is('economics','economics/*') ? 'active':'' }}">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            Economies
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('economics')}}">SOUTH AMERICA</a></li>
                            <li><a href="{{ route('economics')}}">CENTRAL AMERICA</a></li>
                            <li><a href="{{ route('economics')}}">THE CARIBBEAN</a></li>
                        </ul>
                    </li>
                    */ ?>
                    <li class="{{ (isset($selectedMenu) && $selectedMenu == 'countries') ? 'active':'' }} dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="{{ url(getLangName().'/countries')}}">{{ __('header.economics') }}</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url(getLangName().'/countries/south-america')}}">SOUTH AMERICA</a></li>
                            <li><a href="{{ url(getLangName().'/countries/caribbean')}}">CARIBBEAN</a></li>
                            <li><a href="{{ url(getLangName().'/countries/central-america')}}">CENTRAL AMERICA</a></li>                            
                            <li><a href="{{ url(getLangName().'/countries/north-america')}}">NORTH AMERICA</a></li>                            
                        </ul>                        
                    </li>
                    <li class="dropdown {{ \Request::is(getLangName().'/analyzer',getLangName().'/analyzer/*') ? 'active':'' }}">
                        <a href="{{ url(getLangName().'/analyzer') }}">
                            {{ __('header.analyzer') }}
                        </a>
                        <?php /*
                        <ul class="dropdown-menu">
                            @if(\Request::is('analyzer','analyzer/*'))                            
                                <li><a href="javascript:void(0);" onclick="navigateToDiv('relval')">{{ __('header.relval') }}</a></li>
                                <li><a href="javascript:void(0);" onclick="navigateToDiv('regression')">{{ __('header.regression') }}</a></li>
                                <li><a href="javascript:void(0);" onclick="navigateToDiv('slope')">{{ __('header.slope') }}</a></li>
                            @else
                                <li><a href="{{ url('analyzer/relval') }}">{{ __('header.relval') }}</a></li>
                                <li><a href="{{ url('analyzer/regression') }}">{{ __('header.regression') }}</a></li>
                                <li><a href="{{ url('analyzer/slope') }}">{{ __('header.slope') }}</a></li>                            
                            @endif
                        </ul>
                         */ ?>
                    </li>
                    <?php /*
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            About
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('about')}}">WHY EMFI</a></li>
                            <li><a href="#">SERVICES</a></li>
                            <li><a href="#">PEOPLE</a></li>
                        </ul>
                    </li>
                    */ ?>
                    <li class="dropdown {{ \Request::is(getLangName().'/about') ? 'active':'' }}">
                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="{{ url(getLangName().'/about')}}">{{ __('header.about') }}</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url(getLangName().'/about#background')}}">BACKGROUND</a></li>
                            <li><a href="{{ url(getLangName().'/about#team')}}">TEAM</a></li>
                            <li><a href="{{ url(getLangName().'/about#clients')}}">CLIENTS</a></li>
                            <li><a href="{{ url(getLangName().'/about#candidates')}}">CANDIDATES</a></li>
                            <li><a href="{{ url(getLangName().'/about#presence')}}">PRESENCE</a></li>
                        </ul>                        
                    </li>
                    <li class="{{ \Request::is(getLangName().'/contact') ? 'active':'' }}">
                        <a href="{{ url(getLangName().'/contact')}}">{{ __('header.contact') }}</a>
                    </li>
                    <li class="">
                        <a href="#">{{ __('header.login') }}</a>
                    </li>                    
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </nav>
</div>