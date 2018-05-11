<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-7 clearfix">
                <div class="footer_block">
                    <h3>{{ __('footer.quick_links') }}</h3>
                    <ul>
                        <li><a href="{{ url(getLangName())}}">{{ __('footer.home') }}</a></li>
                        <li><a href="{{ url(getLangName().'/about')}}">{{ __('footer.about') }}</a></li>
                        <li><a href="{{ url(getLangName().'/contact')}}">{{ __('footer.contact') }}</a></li>
                        <li><a href="{{ url(getLangName())}}">{{ __('footer.login') }}</a></li>
                    </ul>
                </div>
                <div class="footer_block">
                    <h3>{{ __('footer.legal_info') }}</h3>
                    <ul>
                        <li><a href="{{ url(getLangName().'/terms-of-uses') }}">{{ __('footer.scam') }}</a></li>
                        <li><a href="{{ url(getLangName().'/cookies') }}">{{ __('footer.cookies') }}</a></li>
                        <li><a href="{{ url(getLangName().'/privacy-statements') }}">{{ __('footer.privacy_statements') }}</a></li>
                        <li><a href="{{ url(getLangName().'/terms-of-uses') }}">{{ __('footer.terms_of_uses') }}</a></li>
                    </ul>
                </div>
                <div class="footer_block">
                    <h3>{{ __('footer.reg_office') }}</h3>
                    <address>
                        {{ __('footer.32_devonshir_pl') }}<br>
                        {{ __('footer.london_ground_fl') }}<br>
                        {{ __('footer.london_w1g_6jl') }}<br>
                        {{ __('footer.united_kingdom') }}
                    </address>
                </div>
            </div>
            <div class="col-md-5">
                <div class="social">
                    <ul>
                        <li><a target="_blank" href="https://www.facebook.com/EMFISecurities/" class="rounded_full"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a target="_blank" href="https://twitter.com/EmfiSecurities" class="rounded_full"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li><a target="_blank" href="https://www.linkedin.com/company/emfi-securities-limited/" class="rounded_full"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                        <li><a target="_blank" href="{{ url(getLangName().'/contact/enquiry')}}" class="rounded_full"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>
                        
                    </ul>
                </div>
                <p class="ftr_txt">&copy; {{ date('Y') }} {{ __('footer.copy_rights') }}. <br>{{ __('footer.copy_rights_1') }}.<br>{{ __('footer.copy_rights_2') }}.<br>{{ __('footer.copy_rights_3') }}.</p>
            </div>
        </div>
    </div>
</footer>
<div id="AjaxLoaderDiv" style="display: none;z-index:99999 !important;">
    <div style="width:100%; height:100%; left:0px; top:0px; bottom:0; right:0; position:fixed; opacity:0.8; filter:alpha(opacity=80); background:#fff; z-index:999999999;">
    </div>
    <div style="float:left;width:100%; left:0px; top:50%; text-align:center; position:fixed; padding:0px; z-index:999999999;">
        <!--<img src="{{ asset('/images/ajax-loader.gif') }}" />-->
        <div class="dual-ring"><img src="{{ asset('themes/emfi/images/emfi-logo-dark.png') }}" alt="EMFI Securities" /></div>
        
    </div>
</div>
