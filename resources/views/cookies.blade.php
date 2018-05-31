@extends('emfi_layout')

@section('content')

<?php $check_performance = \Session::get('check_performance');?>
<?php $check_user_preference = \Session::get('check_user_preference');?>

<section class="top_section">
  <div class="container">
    <div class="title_belt">
      <h2>{{ $content->title}}</h2>
        <span>{{ __('contact.emfi_group') }}</span>
    </div>
    <div class="about_top_section">
      <div class="row">
        <div class="col-md-12">
          <div class="terms_block">
            {!! $content->description !!}
            <div class="clearfix"></div>
            <input type="button" class="btn save-chk" value="Save" />
            <div class="clearfix"></div>            
          </div>
        </div>
    </div>
  </div>
</section>

@stop

@section('scripts')
<script src="{{ asset('themes/emfi/js/about.js') }}"></script>
<script>
  $(document).ready(function(){
    var i = 0;

    $("input[type='checkbox']").each(function(){
      if(i == 0)
        $(this).attr("id","check_performance");
      else if(i == 1)
        $(this).attr("id","check_user_preference");
      else
        $(this).prop("disabled","disabled");

      i++;
    });

    <?php /*
    $(document).on("click","#check_performance",function(){

      var isChecked = 0;
      if($(this).is(":checked"))
        isChecked = 1;

        $.ajax({
          url: '{{ url('/') }}',
          type: "GET",
          data: 
          {
            'check_performance': 1,
            'isChecked' : isChecked
          },
          success:function(data) {
          },
        });
    });

    $(document).on("click","#check_user_preference",function(){
        var isChecked = 0;
        if($(this).is(":checked"))
          isChecked = 1;

        $.ajax({
          url: '{{ url('/') }}',
          type: "GET",
          data: 
          {
            'check_user_preference': 1,
            'isChecked' : isChecked
          },
          success:function(data) {
          },
        });        
    });
    */ ?>

    $(document).on("click",".save-chk",function(){
        var isChecked = 0;
        if($("#check_performance").is(":checked"))
          isChecked = 1;

        var isChecked1 = 0;
        if($("#check_user_preference").is(":checked"))
          isChecked1 = 1;
        $('#AjaxLoaderDiv').fadeIn('slow');
        $.ajax({
          url: '{{ url('/') }}',
          type: "GET",
          data: 
          {
            'check_performance': isChecked,
            'check_user_preference': isChecked1,
            'action': 'cookie'
          },
          success:function(data) {
            $.bootstrapGrowl("Cookie settings has been saved.", {type: 'success', delay: 4000});
            $('#AjaxLoaderDiv').fadeOut('slow');
          },
        });
    });

    setTimeout(function(){
        @if(session("check_performance") == 1)
          $("#check_performance").prop("checked",false);
        @else
          $("#check_performance").prop("checked",true);
        @endif

        @if(session("check_user_preference") == 1)
          $("#check_user_preference").prop("checked",false);
        @else
          $("#check_user_preference").prop("checked",true);
        @endif
    },1200);
  });
</script>
@stop
