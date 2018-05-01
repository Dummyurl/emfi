@extends('layout')


@section('content')
<section class="top_section top_bg economics_bg">
    <div class="container">
        <div class="title_belt">
            <h2>{{ __('contact.contact_us') }}</h2>
            <span>{{ __('contact.help_you') }}</span> </div>
        <div class="contact_form">
            <div class="row">
                <p>{{ __('contact.any_qtn') }}.</p>
                {!! Form::open(['url' => 'contact-form', 'id' => 'contact_form_id', 'enctype' => 'multipart/form-data']) !!}
                <!-- <form action="{{ url('contact-form')}}" method="post" id="contact_form_id"> -->
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::text('first_name', null, ['class' => 'form-control' , 'placeholder' => __('contact.first_name'), 'required' => 'required']) !!}
                        <!-- <input name="first_name" class="form-control" id="" placeholder="{{ __('contact.first_name') }}" type="text" required="required"> -->
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => __('contact.last_name') , 'required' => 'required']) !!}
                        <!-- <input name="last_name" class="form-control" id="" placeholder="{{ __('contact.last_name') }}" type="text" required="required"> -->
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::text('organization', null, ['class' => 'form-control', 'placeholder' => __('contact.organization') , 'required' => 'required']) !!}
                        <!-- <input name="last_name" class="form-control" id="" placeholder="{{ __('contact.last_name') }}" type="text" required="required"> -->
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::text('country' , null , ['class' => 'form-control' , 'placeholder' => __('contact.country') , 'required' => 'required']) !!}
                        <!-- <input name="country" class="form-control" id="" placeholder="{{ __('contact.country') }}" type="text" required="required"> -->
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::text('phone' , null , ['class' => 'form-control' , 'placeholder' => __('contact.phone') , 'required' => 'required']) !!}
                        <!-- <input name="phone" class="form-control" id="" placeholder="{{ __('contact.country') }}" type="text" required="required"> -->
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::text('email' , null , ['class' => 'form-control' , 'placeholder' => __('contact.email') , 'required' => 'required']) !!}
                        <!-- <input name="email" class="form-control" id="" placeholder="{{ __('contact.email') }}" type="text" required="required"> -->
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::select('business_unit' , ['' => __('contact.business_unit')] + ['ASSET MANAGEMENT' => __('header.asset_management'), 'WEALTH MANAGEMENT' => __('header.wealth_management') , 'INVESTMENT BANKING' =>  __('header.investment_banking') , 'PRIME BROKERAGE' => __('header.prime_brokerage') , 'DATA ANALYTICS' => __('header.data_analytics')], null, ['class' => 'form-control' ,  'required' => 'required']) !!}
                        <!-- <input name="company" class="form-control" id="" placeholder="{{ __('contact.company') }}" type="text" required="required"> -->
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::select('subject' , ['' => __('contact.subject')] + ['ENQUIRY' => __('header.enquiry') , 'FEEDBACK' => __('header.feedback') , 'COMPLAINT' => __('header.complaint') , 'CAREERS' => __('header.careers') , 'LOCATIONS' => __('header.locations')], strtoupper($type), ['class' => 'form-control' ,  'required' => 'required']) !!}
                        <!-- <input name="subject" class="form-control" id="" placeholder="{{ __('contact.subject') }}" type="text" required="required"> -->
                    </div>
                </div>
                <div class="col-md-12 txtarea">
                    <div class="form-group">
                        {!! Form::textarea('message', null, ['class' => 'form-control' , 'cols' => '', 'rows' => '4' , 'placeholder' => __('contact.message'), 'required' => 'required']) !!}
                        <!-- <textarea name="message" cols="" rows="4" class="form-control" placeholder="{{ __('contact.message') }}" required="required"></textarea> -->
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::file('attachment', ['class' => 'form-control']) !!}
                        <!-- <textarea name="message" cols="" rows="4" class="form-control" placeholder="{{ __('contact.message') }}" required="required"></textarea> -->
                    </div>
                </div>
                <div class="col-md-12 submit_btn">
                    <div class="form-group">
                        <button class="btn">{{ __('contact.send') }}</button>
                    </div>
                </div>
                <!-- </form> -->
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>
<section class="office_details_map">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="office_details">
                    <h3>LONDON</h3>
                    <address>
                        <strong>EMFI</strong> SECURITIES<br>
                        32 Devonshire Pl<br>
                        London, W1G 6JL<br>
                        United Kingdom
                    </address>
                    <a href="mailto:lndon@emfisecurities.com">lndon@emfisecurities.com</a>
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
                    <h3>ZÜRICH</h3>
                    <address>
                        <strong>EMFI</strong> SECURITIES<br>
                        Leonhardstrasse 1<br>
                        8001 Zürich<br>
                        Switzerland
                    </address>
                    <a href="mailto:zurich@emfisecurities.com">zurich@emfisecurities.com</a>
                </div>
            </div>
            <div class="col-md-8 col-md-pull-4">
                <div class="office_map map_mrgn_r">
                    <div id="map2" class="map"></div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA4se7HxOqOpUPcelVjD7Odc_BBP4qdqHE&callback=init"
type="text/javascript"></script>
<script src="{{ asset('themes/frontend/js/contact.js') }}"></script>
@stop
