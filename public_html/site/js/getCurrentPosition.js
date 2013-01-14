if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(Show_Maps);
} else {
    alert('La geo-localizzazione NON è possibile...');
}

/**
 * Mostro la mappa in home page
 * @param posizione
 * @constructor
 */
function Show_Maps(posizione) {

    var latitude = posizione.coords.latitude;
    var longitude = posizione.coords.longitude;

    $.ajax({
        type:'POST',
        url:'http://bazoomba/ajax/region/',
        data:{
            latitude:latitude,
            longitude:longitude
        },
        dataType:'html',
        success:function (msg) {
            $('#name_region').html(msg);
        },
        error:function () {
            alert('Chiamata fallita, si prega di riprovare...');
        }
    });

    $('#maps').gmap('addMarker', {
        'position':new google.maps.LatLng(latitude, longitude),
        'icon': 'http://bazoomba/site/img/world.png',
        'bounds':true
    }).click(function () {
            $('#maps').gmap('openInfoWindow', {
                'content': 'io...'
            }, this);
        });

    $('#maps').gmap().bind('init', function () {
        $.getJSON('http://bazoomba/ajax/geolocation/latitude/' + latitude + '/longitude/' + longitude, function (data) {
            $.each(data, function (i, marker) {
                $('#maps').gmap('addMarker', {
                    'position':new google.maps.LatLng(marker.latitude, marker.longitude),
                    'icon': 'http://bazoomba/site/img/bigcity.png',
                    'bounds':true
                }).click(function () {
                        $('#maps').gmap('openInfoWindow', {
                            'content':marker.title
                        }, this);
                    });
            });
        });
    });

//
}





