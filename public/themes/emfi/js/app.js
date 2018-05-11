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
    if ($(window).width() >= 1280) 
    {
        var vpHeight = $(window).height();
        var ht1 = vpHeight - 70;
        $('.home_slider').css('height', ht1 + 'px').css('padding-top', (ht1 / 2) + 'px');
		$('.geo_chart').css('height', vpHeight + 'px');
    }
}

$(window).on('resize', function () {
    getHeight();
    if ($(window).width() < 1280) 
    {
        $('.home_slider').css('height', 'auto').css('padding-top', 'inherit');
		$('.geo_chart').css('height', 'auto');
    }
});

$(document).ready(function () {
    getHeight();
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
        $(".disclaimer_show").hide();
        // $(".parallax-mirror").css("top","0px");
        $(".nav_wrapper.nav_discl").removeClass("nav_discl");
       $("body").css("padding-top","0px");
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
       $("body").css("padding-top","174px");
    }
});
