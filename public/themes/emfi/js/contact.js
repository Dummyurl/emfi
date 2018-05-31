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
     anchor.top = anchor.top - 100;
     // console.log("top" + anchor.top);
     $('html, body').animate({
             scrollTop: anchor.top
     }, 500);
 }
};

    function init2(){
    var london = new google.maps.LatLng(51.522117, -0.150337);
    var zurich = new google.maps.LatLng(47.3717306, 8.5364392);
    var newyork = new google.maps.LatLng(40.7588461, -73.9919443);
    
    var london_address = '<b> EMFI Securities Limited</b> <br><br> 32 Devonshire Pl<br>London, W1G 6JL<br> United Kingdom';
    var zurich_address = '<b>EMFI Wealth AG</b> <br><br>Bahnhofstrasse 58<br>8001 Zurich<br>Switzerland';
    var newyork_address = '<b>EMFI Capital LLC</b> <br><br> 598 9th Ave,<br>New York, NY 10036 <br>United States';

    var element_london = 'map';
    var element_zurich = 'map2';
    var element_newyork = 'map3';

    init1(london, element_london, london_address);
    init1(zurich, element_zurich, zurich_address);
    init1(newyork, element_newyork, newyork_address);
}

function init1(location, element, address){
    var mapOptions = {
            zoom: 16,
            center: location,
            scrollwheel : false,
            mapTypeControl: false,
            streetViewControl: false,
    styles : [{
            featureType: "all",
            elementType: "all",
            stylers: [{ saturation: -100 }]
    }]
    };
    var mapElement = document.getElementById(element);
    var infowindow = new google.maps.InfoWindow({
                        content: address,
                        size: new google.maps.Size(150,50)
                    });
    var map = new google.maps.Map(mapElement, mapOptions);

    var marker = new google.maps.Marker({
                position: location,
                map: map,
                icon: '/themes/emfi/images/map-pin.png',
                title: 'EMFI Securities'
                });
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map,marker);
    });

}


$(document).ready(function () {
    $('#contact_form_id').submit(function()
    {
        // if (true)
            //{
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
                            $.bootstrapGrowl(result.msg, {type: 'success', delay: 10000});
                            // window.location.reload();    
                            $('#contact_form_id')[0].reset();
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
            //}
            
            return false;
    });
});
