 function init2(){
    var london = new google.maps.LatLng(51.522117, -0.150337);
    var address = '<b> EMFI Securities Limited</b> <br><br> 32 Devonshire Pl<br>London, W1G 6JL<br> United Kingdom';
    var element_london = 'map';
    init1(london, element_london, address);
}
function assetmanagement(){
    //40.75941,-73.9961324,15.75
    var newyork = new google.maps.LatLng(40.75941, -73.9961324);
    var element_newyork = 'map';
    var address = '<b>EMFI Capital LLC</b> <br><br> 598 9th Ave,<br>New York, NY 10036 <br>United States';
    init1(newyork, element_newyork, address);
}
function wealthmanagement(){
    //47.3717306,8.5364392
    var newyork = new google.maps.LatLng(47.3717306, 8.5364392);
    var element_newyork = 'map';
    var address = '<b>EMFI Wealth AG</b> <br><br>Bahnhofstrasse 58<br>8001 Zurich<br>Switzerland';
    init1(newyork, element_newyork, address);
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