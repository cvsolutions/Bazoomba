$(document).ready(function(){

    var scegli = '<option value="0">Scegli...</option>';
    var attendere = '<option value="0">Attendere...</option>';

    $("select#province").html(scegli);
    $("select#province").attr("disabled", "disabled");
    $("select#city").html(scegli);
    $("select#city").attr("disabled", "disabled");
    $("select#sub_category").html(scegli);
    $("select#sub_category").attr("disabled", "disabled");

    /**Provincie */
    $("select#region").change(function(){
        var regione = $("select#region option:selected").attr('value');
        $("select#province").html(attendere);
        $("select#province").attr("disabled", "disabled");

        $.post("http://bazoomba/ajax/province", {
            id_reg:regione
        }, function(data){
            $("select#province").removeAttr("disabled");
            $("select#province").html(data);
        });
    });

    /**Città */
    $("select#province").change(function(){
        var provincia = $("select#province option:selected").attr('value');
        $("select#city").html(attendere);
        $("select#city").attr("disabled", "disabled");

        $.post("http://bazoomba/ajax/city", {
            id_pro:provincia
        }, function(data){
            $("select#city").removeAttr("disabled");
            $("select#city").html(data);
        });
    });

    /** Categorie */
    $("select#category").change(function(){
        var category = $("select#category option:selected").attr('value');
        $("select#sub_category").html(attendere);
        $("select#sub_category").attr("disabled", "disabled");

        $.post("http://bazoomba/ajax/subcategory", {
            id_cat:category
        }, function(data){
            $("select#sub_category").removeAttr("disabled");
            $("select#sub_category").html(data);
        });
    });

    /** Map */
    var geocoder;
    var map;
    var marker;
    var infocontent = 'Se non sei soddisfatto della posizione, sposta il marcatore nella posizione corretta.<br /> E\' molto importante segnalare la posizione corretta dell\'oggetto.';
    var vikey = [{
        stylers: [{
            gamma: 0.52
        },{
            saturation: 11
        } ]
    }];

    function initialize(){
        //MAP
        var latlng = new google.maps.LatLng(41.9015141, 12.4607737);
        var options = {
            zoom: 6,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: vikey
        };

        map = new google.maps.Map(document.getElementById("map_canvas"), options);
        geocoder = new google.maps.Geocoder();

        var infowindow = new google.maps.InfoWindow({
            content: infocontent
        });

        marker = new google.maps.Marker({
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            position: latlng
        });

        infowindow.open(map,marker);

    }


    initialize();

    $(function() {
        $("#address").autocomplete({
            //This bit uses the geocoder to fetch address values
            source: function(request, response) {
                geocoder.geocode( {
                    'address': request.term
                }, function(results, status) {
                    response($.map(results, function(item) {
                        return {
                            label:  item.formatted_address,
                            value: item.formatted_address,
                            latitude: item.geometry.location.lat(),
                            longitude: item.geometry.location.lng()
                        }
                    }));
                })
            },
            //This bit is executed upon selection of an address
            select: function(event, ui) {
                $("#latitude").val(ui.item.latitude);
                $("#longitude").val(ui.item.longitude);
                var location = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);
                marker.setPosition(location);
                map.setCenter(location);
                map.setZoom(14);
            }
        });
    });

    //Add listener to marker for reverse geocoding
    google.maps.event.addListener(marker, 'drag', function() {
        geocoder.geocode({
            'latLng': marker.getPosition()
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('#address').val(results[0].formatted_address);
                    $('#latitude').val(marker.getPosition().lat());
                    $('#longitude').val(marker.getPosition().lng());
                }
            }
        });
    });

    $("#map_canvas").fadeOut(500);
    $("#address").keypress(function() {
        $("#map_canvas").fadeIn(2000);
    });

});


$(function() {
    /** Jquery Validation Form Add Ads */
    $("#newShop").validate({
        rules:{
            'category': {
                required: true,
                min: 1
            },
            'sub_category': {
                required: true,
                min: 1
            },
            'region': {
                required: true,
                min: 1
            },
            'province': {
                required: true,
                min: 1
            },
            'city': {
                required: true,
                min: 1
            },
            'price':{
                required: true,
                digits: true
            },
            'title': {
                required: true,
                minlength: 8,
                maxlength: 50
            },
            'description': {
                required: true,
                minlength: 20
            },
            'address': {
                required: true
            },
            'terms': {
                required: true,
                min: 1
            }
        },
        messages:{
            'category':{
                required: "Il campo categoria è obbligatorio",
                min: "Il campo categoria è obbligatorio"
            },
            'sub_category':{
                required: "Il campo categoria è obbligatorio",
                min: "Il campo sotto categoria è obbligatorio"
            },
            'region':{
                required: "Il campo regione è obbligatorio",
                min: "Il campo regione è obbligatorio"
            },
            'province':{
                required: "Il campo provincia è obbligatorio",
                min: "Il campo provincia è obbligatorio"
            },
            'city':{
                required: "Il campo città è obbligatorio",
                min: "Il campo città è obbligatorio"
            },
            'price':{
                required: "Il campo prezzo è obbligatorio",
                digits: "Inserisci solo numeri"
            },
            'title': {
                required: "Il campo titolo è obbligatorio",
                minlength: "Il campo titolo deve essere composto da almeno 8 caratteri",
                maxlength: "Il campo titolo deve essere max 50 caratteri"
            },
            'description': {
                required: "Il campo descrizione obbligatorio",
                minlength: "Il campo descrizione deve essere composto da almeno 20 caratteri"
            },
            'address':{
                required: "Il campo indirizzo è obbligatorio!"
            },
            'terms': {
                required: "Il campo condizioni è obbligatorio!",
                min: "Il campo condizioni è obbligatorio!"
            }
        },
        submitHandler: function(form) {
            $.ajax({
                type: 'POST',
                url: 'http://bazoomba/ajax/newshop',
                dataType: 'json',
                data: {
                    region: $('#region').val(),
                    province: $('#province').val(),
                    city: $('#city').val(),
                    category: $('#category').val(),
                    sub_category: $('#sub_category').val(),
                    type: $('#type').val(),
                    title: $('#title').val(),
                    description: $('#description').val(),
                    price: $('#price').val(),
                    address: $('#address').val(),
                    latitude: $('#latitude').val(),
                    longitude: $('#longitude').val()
                },
                beforeSend: function() {
                    $('#submit').hide();
                    $('img#loading').show();
                },
                success: function(data) {
                    document.location.href="http://bazoomba/shop/media/id/"+data.id;
                },
                error: function() {
                    alert("Errore, se il problema persiste contatta l'assistenza");
                    $('#submit').show();
                }
            }); //Ajax
        } //submihandler
    });
/** End Form_Add_Ads */


});