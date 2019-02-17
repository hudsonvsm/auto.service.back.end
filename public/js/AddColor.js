$(document).ready(function() {
    // color
    $('#add-new-color-modal').on('hide.bs.modal', function (e) {
        $(this).find('input#color').val('');
    });

    $("#add-new-color").on('submit', function (event) {
        event.preventDefault();

        var $this = $(this);
        var inputColor = $this.find('input#color');

        $.post({
            url: URL + '/Color',
            data: {
                name: inputColor.val()
            }
        }, 'json').done(function (data, textStatus, jqXHR) {
            if (textStatus === "success") {
                alert(i18n[textStatus].toUpperCase());

                var color = $('#color_id');
                color.val(data.id);
                if (Object.keys(data).length === 1) {
                    data.name = inputColor.val();

                    appendNewOptionToSelect(color, data, data.id, data.name, true);
                }

                $('#add-new-color-modal').modal('toggle');

                return false;
            }

            alert(i18n['error'].toUpperCase());
        })
        .fail(function (data, textStatus, jqXHR) {
            console.log('fail big time');
        })
        .always(function (data, textStatus, jqXHR) {
            console.log('always');
        });
    });
});