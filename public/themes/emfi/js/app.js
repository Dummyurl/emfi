
function getRoundedMinValueForYBenchmark($val)
{
    if($val >= 0 && $val <= 1)
    {
        $val = 0;
    }
    else
    {
        if($val > 10)
        $val = ($val*0.90);
        // $val = $val - 1;
    }

    return $val;
}

function getRoundedMaxValueForYBenchmark($val)
{
    if($val > 10)
    $val = ($val*1.10);
    return $val;
}


function getRoundedMinValueForY($val)
{
    if($val >= 0 && $val <= 1)
    {
        $val = 0;
    }
    else
    {
        if($val > 10)
        $val = ($val*0.90);
    }

    return $val;
}



function getRoundedMinValue($val)
{
    if($val >= 0 && $val <= 1)
    {
        $val = 0;
    }
    else
    {
        $val = $val - 1;
    }

    return $val;
}

function getRoundedMaxValueForY($val)
{
    if($val > 10)
    $val = ($val*1.10);
    return $val;
}

function getRoundedMaxValue($val)
{
    $val = $val + 1;
    return $val;
}
function getHeight() 
{
	if($('.disclaimer_show').size() > 0){
		return false;
	}
    if ($(window).width() >= 1280) 
    {
        var vpHeight = $(window).height();
        var ht1 = vpHeight - 70;
        $('.home_slider').css('height', ht1 + 'px').css('padding-top', (ht1 / 2) + 'px');
		//$('.geo_chart').css('height', vpHeight + 'px');
		$('.services').css('height', ht1 + 'px').css('padding-top', (ht1 / 2) + 'px');
    }
}

function GetDecimalFormat($val){
    vAxisFormat = '0';
    if($val ==1){
        vAxisFormat = '0.00';// For Price
    } else if($val ==2){
        vAxisFormat = '0.00';// For Yield
    }else if($val ==3){
        vAxisFormat = '0'; // For Sprede
    }
    return vAxisFormat;
}

$(window).on('resize', function () {
        getHeight();
    if ($(window).width() < 1280) 
    {
        $('.home_slider').css('height', 'auto').css('padding-top', '');
        $('.geo_chart').css('height', 'auto');
        $('.services').css('height', 'auto').css('padding-top','');
    }
});

$(document).ready(function () {
	getHeight();
	if ($(window).width() >= 1280) {
        var vpHt = $(window).height();
		$('.geo_chart').css('height', vpHt + 'px');
		//alert(vpHt);
    }
    
    if ($(window).width() > 767) 
    {
        $('#navbar .dropdown, .rightlinks .dropdown').hover(function () {
            $(this).find('.dropdown-menu').first().stop(true, true).slideDown(10);
        }, function () {
            $(this).find('.dropdown-menu').first().stop(true, true).slideUp(200);
        });
        
        if($('.carousel').size() > 0)
        {
            $('.carousel').carousel({
                interval: 3000
            });            
        }
    }
    
    if($(".home_carousel").size() > 0)
    {
        $(".home_carousel").owlCarousel({
            loop: false,
            nav: true,
            margin: 0,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: false,
            responsive: {
                0: {
                    items: 1
                },
                640: {
                    items: 1
                },
                768: {
                    items: 1
                },
                992: {
                    items: 1
                }
            }
        })
        $(".home_carousel .owl-prev").html('<i class="fa fa-angle-left"></i>');
        $(".home_carousel .owl-next").html('<i class="fa fa-angle-right"></i>');        
    }    
});


$(document).ready(function() {

    $(document).on('click', '.close_disclaimer', function () {
  //      $(".disclaimer_show").hide();
		$(".disclaimer_block").remove();
		$('.disclmr_on').removeClass('disclmr_on');
        // $(".parallax-mirror").css("top","0px");
        $(".nav_wrapper.nav_discl").removeClass("nav_discl");
		getHeight();
		
        $("body").css("padding-top","");
		$('footer').css("margin-top","");
		//$('.top_section').css("padding-top","");
        $.ajax({
            type: "GET",
            url: '/',
            data:{close_disclaimer: 1},
            success: function (result)
            {
            }
        });
    });
    if($('.disclaimer_show').length > 0) {
		var dscHt = $('.disclaimer_show').height()+10;
		var navHt = $('.navbar').height();
		console.log("disclaimer -> ", dscHt,navHt);
       $("body").css("padding-top",dscHt+navHt,"px");
       //$("body").css("padding-top","165px");
	   $('.home_slider').css('height', 'auto').css('padding-top', '').addClass('disclmr_on');
	   $('.services').addClass('disclmr_on');
	   $('.top_section').addClass('disclmr_on');
	   $('.geo_chart').addClass('disclmr_on');
	   //$('.geo_chart').css('height', 'auto');
	   $('footer').css("margin-top","40px");
	   //$('.top_section').css("padding-top","0");
    }
});
