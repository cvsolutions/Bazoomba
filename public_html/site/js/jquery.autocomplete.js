$(document).ready(function() {

    //add class in careusal
    $('div.item:first').addClass("active");

    // AUTOCOMPLETE
    var termTemplate = '<strong>%s</strong>';
    $("#q").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "http://bazoomba/ajax/autocomplete",
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.label,
                            value: item.value,
                            id: item.id,
                            type: item.type
                        };
                    }));
                }
            });
        },
        open: function(e, ui) {
            $(this).data("autocomplete").menu.element.width(350);
            var acData = $(this).data('autocomplete');
            acData.menu.element.find('a').each(function() {
                var me = $(this);
                var regex = new RegExp(acData.term, "gi");
                me.html(me.text().replace(regex, function(matched) {
                    return termTemplate.replace('%s', matched);
                }));
            });
        },
        dataType: "json",
        minLength: 3,
        delay: 3,
        select: function(event, ui) {
            $('#ads').val(ui.item.id);
            $('#type').val(ui.item.type);
        }

    });

    
    


});