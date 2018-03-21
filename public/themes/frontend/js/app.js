$(document).ready(function(){
    
        if( $(window).width() > 767) 
        {
            $('#navbar .dropdown, .rightlinks .dropdown').hover(function() {
                $(this).find('.dropdown-menu').first().stop(true, true).slideDown(105);
            }, function() {
                $(this).find('.dropdown-menu').first().stop(true, true).slideUp(150);
            });
            
            $('.carousel').carousel({
                interval: 3000
            });
	}    

        $(window).scroll(function() {
            if ($(this).scrollTop() >= 60) {
                $('.nav_wrapper').addClass('sticky');
            }
            else {
                $('.nav_wrapper').removeClass('sticky');
            }
        });
        
        if($(".home_carousel").size() > 0)
        {
            $(".home_carousel").owlCarousel({
                loop:false,
                nav:true,
                margin:0,
                autoplay:true,
                autoplayTimeout:1000,
                autoplayHoverPause:false,
                responsive:{
                    0:{
                        items:1
                    },
                            640:{
                        items:1
                    },
                    768:{
                        items:1
                    },
                    992:{
                        items:1
                    }
                }
            });
            $(".home_carousel .owl-prev").html('<i class="fa fa-angle-left"></i>');
            $(".home_carousel .owl-next").html('<i class="fa fa-angle-right"></i>');                    
        }    
});