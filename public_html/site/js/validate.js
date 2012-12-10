$(function() { /** Jquery Validation Form Add Ads */
    $("#newShop").validate({
        rules: {
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
            'price': {
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
        messages: {
            'category': {
                required: "Il campo categoria è obbligatorio",
                min: "Il campo categoria è obbligatorio"
            },
            'sub_category': {
                required: "Il campo categoria è obbligatorio",
                min: "Il campo sotto categoria è obbligatorio"
            },
            'region': {
                required: "Il campo regione è obbligatorio",
                min: "Il campo regione è obbligatorio"
            },
            'province': {
                required: "Il campo provincia è obbligatorio",
                min: "Il campo provincia è obbligatorio"
            },
            'city': {
                required: "Il campo città è obbligatorio",
                min: "Il campo città è obbligatorio"
            },
            'price': {
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
            'address': {
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
                    tags: $('#tags').val(),
                    price: $('#price').val(),
                    address: $('#address').val(),
                    latitude: $('#latitude').val(),
                    longitude: $('#longitude').val(),
                    video: $('#video').val(),
                    url_video: $('#url').val()
                },
                beforeSend: function() {
                    $('#submit').hide();
                    $('img#loading').show();
                },
                success: function(data) {
                    document.location.href = "http://bazoomba/shop/media/id/" + data.id;
                },
                error: function() {
                    alert("Errore, se il problema persiste contatta l'assistenza");
                    $('#submit').show();
                }
            }); //Ajax
        } //submihandler
    }); /** End Form_Add_Ads */

    /**Form_New_User*/
    $("#newUser").validate({
        rules: {
            'type': {
                required: true,
                min: 1
            },
            'name':{
                required: true,
                minlength: 5
            },
            'telephone':{
                required: true,
                digits: true
            },
            'pwd': {
                required: true,
                minlength: 5
            },
            'confirm': {
                required: true,
                minlength: 5,
                equalTo: "#pwd"
            },
            'email':{
                required: true,
                email: true,
                remote:{
				url: "http://bazoomba/ajax/controlemail",
				type: "post"
				}
            },
            'captcha-input': {
                required: true
            }
        },
        messages: {
            'type': {
                required: "Il campo Tipologia è obbligatorio",
                min: "Il campo Tipologia è obbligatorio"
            },
            'name': {
                required: "Il campo Nome è obbligatorio",
                minlength: "Il campo Nome deve essere composto da almeno 5 caratteri"
            },
            'telephone': {
                required: "Il campo Nome è obbligatorio",
                digits: "Inserisci solo numeri"
            },
            'pwd': {
                required: "Il campo password obbligatorio",
                minlength: "Il campo password deve essere composto da almeno 5 caratteri"
            },
            'confirm': {
                required: "Il campo Ripeti Password è obbligatorio!",
                minlength: "Il campo password deve essere composto da almeno 5 caratteri",
                equalTo: "La password non è ugaule"
            },
            'email': {
                required: "Il campo email è obbligatorio",
                email: "Inserisci un email valida",
                remote: "Questa email risulta già registrata. Recupera la tua password."
            },
            'captcha-input': {
                required: "Il campo captcha è obbligatorio"
            }
        },
        submitHandler: function(form) {
            $.ajax({
                type: 'POST',
                url: 'http://bazoomba/ajax/newuser',
                dataType: 'json',
                data: {
                    type: $('#type').val(),
                    name: $('#name').val(),
                    telephone: $('#telephone').val(),
                    phone_show: $('input[name=phone_show]:checked').val(),
                    pwd: $('#pwd').val(),
                    email: $('#email').val(),
                    vat: $('#vat').val(),
                    name_company: $('#name_company').val()
                },
                beforeSend: function() {
                    $('#submit').hide();
                    $('img#loading').show();
                },
                success: function(data) {
                    $('#result').html(data.result);
                    setTimeout(function() {
                       document.location.href="http://bazoomba";
                    }, 5000);
                },
                error: function() {
                    alert("Errore, se il problema persiste contatta l'assistenza");
                    $('#submit').show();
                }
            }); //Ajax
        } //submihandler
    }); /** End Form_New_User */


});


