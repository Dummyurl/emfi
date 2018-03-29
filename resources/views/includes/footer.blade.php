<footer>
  <div class="container">
    <div class="row">
      <div class="col-md-7 clearfix">
        <div class="footer_block">
          <h3>Quick Links</h3>
          <ul>
            <li><a href="{{ url('/markets') }}">Markets</a></li>
            <li><a href="{{ url('/')}}">Login</a></li>
            <li><a href="{{ route('contact')}}">Contact</a></li>            
          </ul>
        </div>
        <div class="footer_block">
          <h3>Legal Info</h3>
          <ul>
            <li><a href="{{ route('terms-of-uses') }}">Terms of Use</a></li>
            <li><a href="{{ route('privacy-statements') }}">Privacy Statements</a></li>
            <li><a href="{{ route('cookies') }}">Cookies</a></li>            
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
        <p class="ftr_txt">&copy; {{ date('Y') }} EMFI Securities Limited. All Rights Reserved. <br>
          For Professional Investors Only. Not For Use By Retail Clients.<br>
          Authorised And Regulated By The Financial Conduct Authority.</p>
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
