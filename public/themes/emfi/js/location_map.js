 function init2(){
    var london = new google.maps.LatLng(51.522117, -0.150337);
    var element_london = 'map';
    init1(london, element_london);
}
function init1(location, element){
    var mapOptions = {
            zoom: 16,
            center: location,
            scrollwheel : false,
            mapTypeControl: false,
    styles : [{
            featureType: "all",
            elementType: "all",
            stylers: [{ saturation: -100 }]
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