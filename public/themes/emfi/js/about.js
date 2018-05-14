$(document).ready(function () {

    $("#myNavigation .active a").on('click', function(event) {
        if (this.hash !== "") {
            event.preventDefault();
            var hash = this.hash;
            $('html, body').animate({
                scrollTop: $(hash).offset().top -80
                }, 800, function(){
            });
         }
     });

    $('#careers_form').submit(function()
    {
        if (true)
            {
                $('#AjaxLoaderDiv').fadeIn('slow');
                $.ajax({
                    type: "POST",
                    url: $(this).attr("action"),
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    enctype: 'multipart/form-data',
                    success: function (result)
                    {
                        $('#AjaxLoaderDiv').fadeOut('slow');
                        if (result.status == 1)
                        {
                            $.bootstrapGrowl(result.msg, {type: 'success', delay: 4000});
                            window.location.reload();
                        }
                        else
                        {
                            $.bootstrapGrowl(result.msg, {type: 'danger', delay: 4000});
                        }
                    },
                    error: function (error) {
                        $('#AjaxLoaderDiv').fadeOut('slow');
                        $.bootstrapGrowl("Internal server error !", {type: 'danger', delay: 4000});
                    }
                });
            }

            return false;
    });
});


window. onload = function () {
 var hash = false;
 if(window.location.hash) {
     hash = true;
 }

 if (hash)
 {
     hash = document.URL.substr(document.URL.indexOf('#')+1);
     var anchor = $('#'+hash).offset();
     // console.log("left" + anchor.top);
     anchor.top = anchor.top - 80;
     // console.log("top" + anchor.top);
     $('html, body').animate({
             scrollTop: anchor.top
     }, 500);
 }
};

$(window).load(function () {
if ( $(window).width() > 991) {
var maxHeight = 0;
$('.inner_1').each(function(){
   if ($(this).height() > maxHeight) { maxHeight = $(this).height(); }
});
$('.inner_1').height(maxHeight);

var maxHeight = 0;
$('.inner_2').each(function(){
   if ($(this).height() > maxHeight) { maxHeight = $(this).height(); }
});
$('.inner_2').height(maxHeight);

var maxHeight = 0;
$('.inner_3').each(function(){
   if ($(this).height() > maxHeight) { maxHeight = $(this).height(); }
});
$('.inner_3').height(maxHeight);

var maxHeight = 0;
$('.inner_4').each(function(){
   if ($(this).height() > maxHeight) { maxHeight = $(this).height(); }
});
$('.inner_4').height(maxHeight);
}
var maxHeight = 0;
$('.btns ul li a').each(function(){
   if ($(this).height() > maxHeight) { maxHeight = $(this).height(); }
});
$('.btns ul li a').height(maxHeight);
});

