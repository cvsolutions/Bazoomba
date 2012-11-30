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

            $.post("http://bazoomba2/ajax/province", {
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

            $.post("http://bazoomba2/ajax/city", {
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

            $.post("http://bazoomba2/ajax/subcategory", {
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


