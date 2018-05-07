function getHeight() 
{
    if ($(window).width() >= 1280) 
    {
        var vpHeight = $(window).height();
        var ht1 = vpHeight - 70;
        $('.home_slider').css('height', ht1 + 'px').css('padding-top', (ht1 / 2) + 'px');
    }
}

$(window).on('resize', function () {
    getHeight();
    if ($(window).width() < 1280) 
    {
        $('.home_slider').css('height', 'auto').css('padding-top', 'inherit');
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
            autoplay: false,
            autoplayTimeout: 3000,
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