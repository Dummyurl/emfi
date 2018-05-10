    function init2(){
    var london = new google.maps.LatLng(51.522117, -0.150337);
    var zurich = new google.maps.LatLng(47.379165, 8.545277);
    var element_london = 'map';
    var element_zurich = 'map2';
    init1(london, element_london);
    init1(zurich, element_zurich);
                        }   

function init1(location, element){

                var mapOptions = {
            zoom: 16,

        center: location,             
            scrollwheel : false,

        styles : [{
            featureType: "all",
            elementType: "all",
            stylers: [
              { saturation: -100 }]
        }]
                };

    var mapElement = document.getElementById(element);

                var map = new google.maps.Map(mapElement, mapOptions);

                var marker = new google.maps.Marker({
        position: location,
                    map: map,
                    icon: '/themes/emfi/images/map-pin.png',
                    title: 'EMFI Securities'
                });
            }
        
$(document).ready(function () {
    $('#contact_form_id').submit(function()
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
