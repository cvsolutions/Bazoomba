if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(Show_Maps);
} else {
    alert('La geo-localizzazione NON è possibile');
}

/**
 * Mostro la mappa in home page
 * @param posizione
 * @constructor
 */
function Show_Maps(posizione) {

    var vikey = [
        { stylers:[
            { gamma:0.52 },
            { saturation:11 }
        ] }
    ];

    var latitude = posizione.coords.latitude;
    var longitude = posizione.coords.longitude;

    $.ajax({
        type: 'POST',
        url: 'http://bazoomba/ajax/region/',
        data:{
            latitude:latitude,
            longitude:longitude
        },
        dataType: 'html',
        success:function (msg) {
            $('#name_region').html(msg);
        },
        error:function () {
            alert('Chiamata fallita, si prega di riprovare...');
        }
    });

    var punto = new google.maps.LatLng(latitude, longitude),

        opzioni = {
            zoom:9,
            center:punto,
            styles:vikey,
            mapTypeId:google.maps.MapTypeId.ROADMAP
        },
        contenitore = document.getElementById('maps'),
        mappa = new google.maps.Map(contenitore, opzioni),
        marker = new google.maps.Marker({
            position:punto,
            map:mappa,
            title: 'Tu sei qui!'
        });
}