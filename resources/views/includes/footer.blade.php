<footer>
  <div class="container">
    <div class="row">
      <div class="col-md-7 clearfix">
        <div class="footer_block">
          <h3>{{ __('footer.quick_links') }}</h3>
          <ul>
            <li><a href="{{ url(getLangName())}}">{{ __('footer.login') }}</a></li>
            <li><a href="{{ url(getLangName().'/about')}}">{{ __('footer.about') }}</a></li>
            <li><a href="{{ url(getLangName().'/contact')}}">{{ __('footer.contact') }}</a></li>
          </ul>
        </div>
        <div class="footer_block">
          <h3>{{ __('footer.legal_info') }}</h3>
          <ul>
            <li><a href="{{ url(getLangName().'/terms-of-uses') }}">{{ __('footer.terms_of_uses') }}</a></li>
            <li><a href="{{ url(getLangName().'/privacy-statements') }}">{{ __('footer.privacy_statements') }}</a></li>
            <li><a href="{{ url(getLangName().'/cookies') }}">{{ __('footer.cookies') }}</a></li>
          </ul>
        </div>
        <div class="footer_block">
          <h3>Reg Office</h3>
          <address>
          32 Devonshire Pl<br>
          London W1G 6JL<br>
          United Kingdom
          </address>
        </div>
      </div>
      <div class="col-md-5">
        <div class="social">
          <ul>
            <li><a target="_blank" href="https://www.facebook.com/EMFISecurities/" class="rounded_full"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
            <li><a target="_blank" href="https://twitter.com/EmfiSecurities" class="rounded_full"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
            <li><a target="_blank" href="https://www.linkedin.com/company/emfi-securities-limited/" class="rounded_full"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
          </ul>
        </div>
        <p class="ftr_txt">&copy; {{ date('Y') }} {{ __('footer.copy_rights') }}. <br>{{ __('footer.copy_rights_1') }}.<br>{{ __('footer.copy_rights_2') }}.</p>
      </div>
    </div>
  </div>
</footer>
<div id="AjaxLoaderDiv" style="display: none;z-index:99999 !important;">
    <div style="width:100%; height:100%; left:0px; top:0px; position:fixed; opacity:0; filter:alpha(opacity=40); background:#000000;z-index:999999999;">
    </div>
    <div style="float:left;width:100%; left:0px; top:50%; text-align:center; position:fixed; padding:0px; z-index:999999999;">
        <img src="{{ asset('/images/ajax-loader.gif') }}" />
    </div>
</div>
@if(session("is_close_disclaimer") != 1)
<div class="container disclaimer_show" style="position:fixed;bottom:0;width:100%;z-index: 1000;">
    <div class="row disclaimer ftr_txt" style="background:rgba(5, 27, 52, 0.85);color:#fff">
        <div class="col-md-12">
            <span title="Close" class="pull-right close_disclaimer" style="color:white;cursor: pointer;">X</span>
        </div>
        <div class="container">
            <h4>Disclaimer</h4>
            <p class="text-justify"> 
            Please carefully review our Cookies Policy, Privacy Policy, Terms of Use and related disclaimers before proceeding. We use cookies to provide you with a safe, effective and user-friendly website. All pricing information is indicative only and while it has been obtained from sources that EMFI believes to be reliable, we do not represent or warrant that it is up-to-date, accurate or complete. Actual results may differ substantially from those reflected as past performance is not necessarily indicative of future results. If you disagree with any of our policies or terms, please do not proceed to the EMFI website.</p>
        </div>
    </div>
</div>
@endif
