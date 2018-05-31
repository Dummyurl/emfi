@extends('emfi_layout')


@section('content')
<section class="top_section top_bg economics_bg">
    <div class="container">
        <div class="title_belt">
            <h2>{{ __('contact.contact_us') }}</h2>
            <span>{{ __('contact.help_you') }}</span> </div>
        <div class="contact_form">
            <div class="row">
            <!-- <p>{{ __('contact.any_qtn') }}.</p> -->
            <div class="col-md-6"><figure class="contact-img bgcover"></figure></div>
            <div class="col-md-6">
                
                {!! Form::open(['url' => 'contact-form', 'id' => 'contact_form_id', 'enctype' => 'multipart/form-data']) !!}
                <!-- <form action="{{ url('contact-form')}}" method="post" id="contact_form_id"> -->
               
                    <div class="form-group">
                        {!! Form::text('first_name', null, ['class' => 'form-control' , 'placeholder' => __('contact.first_name')]) !!}
                        <!-- <input name="first_name" class="form-control" id="" placeholder="{{ __('contact.first_name') }}" type="text" required="required"> -->
                    </div>
               
               
                    <div class="form-group">
                        {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => __('contact.last_name') ]) !!}
                        <!-- <input name="last_name" class="form-control" id="" placeholder="{{ __('contact.last_name') }}" type="text" required="required"> -->
                    </div>
               
               
                    <div class="form-group">
                        {!! Form::text('organization', null, ['class' => 'form-control', 'placeholder' => __('contact.organization')]) !!}
                        <!-- <input name="last_name" class="form-control" id="" placeholder="{{ __('contact.last_name') }}" type="text" required="required"> -->
                    </div>
               
               
                    <div class="form-group">
                        {!! Form::text('country' , null , ['class' => 'form-control' , 'placeholder' => __('contact.country')]) !!}
                        <!-- <input name="country" class="form-control" id="" placeholder="{{ __('contact.country') }}" type="text" required="required"> -->
                    </div>
               
               
                    <div class="form-group">
                        {!! Form::text('phone' , null , ['class' => 'form-control' , 'placeholder' => __('contact.phone')]) !!}
                        <!-- <input name="phone" class="form-control" id="" placeholder="{{ __('contact.country') }}" type="text" required="required"> -->
                    </div>
               
               
                    <div class="form-group">
                        {!! Form::text('email' , null , ['class' => 'form-control' , 'placeholder' => __('contact.email') ]) !!}
                        <!-- <input name="email" class="form-control" id="" placeholder="{{ __('contact.email') }}" type="text" required="required"> -->
                    </div>
               
                
                    <div class="form-group">
                        {!! Form::select('business_unit' , ['' => __('contact.business_unit')] + ['1' => __('contact.asset_management'), '2' => __('contact.wealth_management') , '3' =>  __('contact.investment_banking') , '4' => __('contact.prime_brokerage') , '5' => __('contact.data_analytics')], null, ['class' => 'form-control' ]) !!}
                        <!-- <input name="company" class="form-control" id="" placeholder="{{ __('contact.company') }}" type="text" required="required"> -->
                    </div>
               
                
                    <div class="form-group">
                        {!! Form::select('subject' , ['' => __('contact.subject')] + ['ENQUIRY' => __('contact.enquiry') , 'FEEDBACK' => __('contact.feedback') , 'COMPLAINT' => __('contact.complaint') , 'CAREERS' => __('contact.careers') ], strtoupper($type), ['class' => 'form-control' ]) !!}
                        <!-- <input name="subject" class="form-control" id="" placeholder="{{ __('contact.subject') }}" type="text" required="required"> -->
                    </div>
              
              
                    <div class="form-group">
                        {!! Form::textarea('message', null, ['class' => 'form-control' , 'cols' => '', 'rows' => '4' , 'placeholder' => __('contact.message')]) !!}
                        <!-- <textarea name="message" cols="" rows="4" class="form-control" placeholder="{{ __('contact.message') }}" required="required"></textarea> -->
                    </div>
              
              
                    <div class="form-group">
                        {!! Form::file('attachment', ['class' => 'form-control']) !!}
                        <!-- <textarea name="message" cols="" rows="4" class="form-control" placeholder="{{ __('contact.message') }}" required="required"></textarea> -->
                    </div>
                
                    <div class="form-group">
                        <button class="btn">{{ __('contact.send') }}</button>
                    </div>
               
                <!-- </form> -->
                {!! Form::close() !!}
                </div>
            </div> <!-- /row end -->
        </div><!-- /contact_form end -->
    </div>
</section>
<section class="office_details_map">
    <div class="container" id="locations">
        <div class="row">
            <div class="col-md-4">
                <div class="office_details">
                    <h3>{{ __('about.london') }}</h3>
                    <address>
                        <strong>EMFI</strong> Group Limited<br><br>
                        32 Devonshire Pl<br>
                        London, W1G 6JL<br>
                        United Kingdom
                    </address>
                    <a href="mailto:london@emfi.eu">london@emfi.eu</a>
                </div>
            </div>
            <div class="col-md-8">
                <div class="office_map map_mrgn_l">
                    <div id="map" class="map"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-md-push-8">
                <div class="office_details">
                    <h3>ZURICH</h3>
                    <address>
                        <strong>EMFI</strong> Group Limited<br><br>
                        Leonhardstrasse 1<br>
                        8001 Zürich<br>
                        Switzerland
                    </address>
                    <a href="mailto:zurich@emfi.eu">zurich@emfi.eu</a>
                </div>
            </div>
            <div class="col-md-8 col-md-pull-4">
                <div class="office_map map_mrgn_r">
                    <div id="map2" class="map"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="office_details">
                    <h3>NEW YORK</h3>
                    <address>
                        <strong>EMFI</strong> Group Limited<br><br>
                        598 9th Ave<br>
                        New York, NY 10036<br>
                        United States
                    </address>
                    <a href="mailto:newyork@emfi.eu">newyork@emfi.eu</a>
                </div>
            </div>
            <div class="col-md-8">
                <div class="office_map map_mrgn_l">
                    <div id="map3" class="map"></div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop

@section('scripts')
<script src="{{ asset('themes/emfi/js/contact.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA4se7HxOqOpUPcelVjD7Odc_BBP4qdqHE&libraries=places&callback=init2"></script>

@stop
