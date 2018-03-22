<div class="nav_wrapper">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <ul class="rightlinks">
                <li class="dropdown"> 
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Login
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#">PORTFOLIO</a></li>
                        <li><a href="#">RESEARCH</a></li>
                    </ul>
                </li>
                <li class="dropdown"> 
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        English
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#">English</a></li>
                        <li><a href="#">ESPAÑOL</a></li>
                    </ul>
                </li>
            </ul>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></button>
                <a class="navbar-brand white" href="#">
                    <img src="{{ asset('themes/frontend/images/emfi-logo.png') }}" alt="EMFI Securities">
                </a> 
                <a class="navbar-brand dark" href="#">
                    <img src="{{ asset('themes/frontend/images/emfi-logo-dark.png') }}" alt="EMFI Securities">
                </a> 
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Home</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            Market
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('market/equities') }}">EQUITIES</a></li>                            
                            <li><a href="{{ url('market/currencies') }}">CURRENCIES</a></li>
                            <li><a href="{{ url('market/commodities') }}">COMMODITIES</a></li>
                            <li><a href="{{ url('market/rates') }}">RATES</a></li>                            
                            <li><a href="{{ url('market/credit') }}">CREDIT</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            Economies
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('economics')}}">SOUTH AMERICA</a></li>
                            <li><a href="{{ route('economics')}}">CENTRAL AMERICA</a></li>
                            <li><a href="{{ route('economics')}}">THE CARIBBEAN</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            Analyzer
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('analyzer')}}">RELVAL</a></li>
                            <li><a href="{{ route('analyzer')}}">REGRESSION</a></li>
                            <li><a href="{{ route('analyzer')}}">SLOPE</a></li>
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
                    <li><a href="{{ route('about')}}">About</a></li>
                    <li><a href="{{ route('contact')}}">contact</a></li>
                    
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </nav>
</div>