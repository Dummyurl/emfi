<div class="nav_wrapper">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <ul class="rightlinks">
                <li class="dropdown"> 
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        {{ __('header.login') }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#">{{ __('header.portfolio') }}</a></li>
                        <li><a href="#">{{ __('header.research') }}</a></li>
                    </ul>
                </li>
                <li class="dropdown"> 
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        @if(session('locale') == "es")
                            ESPAÑOL
                        @else
                            English
                        @endif
                    </a>
                    <ul class="dropdown-menu">
                        @if(session('locale') == "es")
                            <li><a href="{{ url('change-language/en') }}">English</a></li>
                        @else
                            <li><a href="{{ url('change-language/es') }}">ESPAÑOL</a></li>
                        @endif                        
                    </ul>
                </li>
            </ul>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">{{ __('header.toggle') }}</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></button>
                <a class="navbar-brand white" href="{{ url('/') }}">
                    <img src="{{ asset('themes/frontend/images/emfi-logo.png') }}" alt="EMFI Securities">
                </a> 
                <a class="navbar-brand dark" href="{{ route('home') }}">
                    <img src="{{ asset('themes/frontend/images/emfi-logo-dark.png') }}" alt="EMFI Securities">
                </a> 
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="{{ \Request::is('/','home') ? 'active':'' }}">
                        <a href="{{ route('home') }}">{{ __('header.home') }}</a>
                    </li>
                    <li class="dropdown {{ \Request::is('markets','markets/*') ? 'active':'' }}">
                        <a href="{{ url('markets') }}">
                            {{ __('header.markets') }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('markets/equities') }}">{{ __('header.equities') }}</a></li>
                            <li><a href="{{ url('markets/currencies') }}">{{ __('header.currencies') }}</a></li>
                            <li><a href="{{ url('markets/commodities') }}">{{ __('header.commodities') }}</a></li>
                            <li><a href="{{ url('markets/rates') }}">{{ __('header.rates') }}</a></li>
                            <li><a href="{{ url('markets/credit') }}">{{ __('header.credit') }}</a></li>
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
                    <li class="{{ \Request::is('economics','economics/*') ? 'active':'' }}"><a href="{{ url('economics')}}">{{ __('header.economics') }}</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ __('header.analyzer') }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#">{{ __('header.relval') }}</a></li>
                            <li><a href="#">{{ __('header.regression') }}</a></li>
                            <li><a href="#">{{ __('header.slope') }}</a></li>
                        </ul>
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
                    <li class="{{ \Request::is('about') ? 'active':'' }}"><a href="{{ route('about')}}">{{ __('header.about') }}</a></li>
                    <li class="{{ \Request::is('contact') ? 'active':'' }}"><a href="{{ route('contact')}}">{{ __('header.contact') }}</a></li>
                    
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </nav>
</div>