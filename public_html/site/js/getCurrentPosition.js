if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(Show_Maps);
} else {
    alert('La geo-localizzazione NON � possibile...');
}

/**
 * Mostro la mappa in home page
 * @param posizione
 * @constructor
 */
function Show_Maps(posizione) {

    var latitude = posizione.coords.latitude;
    var longitude = posizione.coords.longitude;
    var bazoomba = 'http://bazoomba';

    $('#lat_maps').val(latitude);
    $('#lng_maps').val(longitude);

    $.ajax({
        type:'POST',
        url: bazoomba + '/ajax/region/',
        data:{
            latitude:latitude,
            longitude:longitude
        },
        dataType:'json',
        success:function (msg) {
            $('#name_region').html(msg.name);
            $('#lat_region').val(msg.lat);
            $('#lng_region').val(msg.lng);
        },
        error:function () {
            alert('Chiamata fallita, si prega di riprovare...');
        }
    });


    $('#maps').gmap('addMarker', {
        'position':new google.maps.LatLng(latitude, longitude),
        'icon': bazoomba + '/site/img/avatar.png',
        'bounds':true
    }).click(function () {
            $('#maps').gmap('openInfoWindow', {
                'content':'la tua posizione esatta...'
            }, this);
        });

    $('#maps').gmap().bind('init', function () {
        $.getJSON(bazoomba + '/ajax/geolocation/latitude/' + latitude + '/longitude/' + longitude, function (data) {
            $.each(data, function (i, marker) {
                $('#maps').gmap('addMarker', {
                    'position':new google.maps.LatLng(marker.latitude, marker.longitude),
                    'icon': bazoomba + '/site/img/bigcity.png',
                    'bounds':true
                }).click(function () {
                        $('#maps').gmap('openInfoWindow', {
                            'content': '<div><a href="' + bazoomba + '/shop/ads/show/'
                            	+ marker.id
                            	+ '/"><img src="http://' + bazoomba + '/image.php?mode=crop&folder=ads&image='
                            	+ marker.photo
                            	+ '&width=350&height=170"></a></div><div style="width: 350px;"><br><p><h4>'
                            	+ marker.title
                            	+ '</h4></p><p>'
                            	+ marker.name_category
                            	+ ' :: <span style="color: #c60f13; font-size: 24px;">� '
                            	+ marker.price + '</span></p></div>'
                        }, this);
                    });
            });
        });
    });

//
}





