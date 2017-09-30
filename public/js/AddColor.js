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
            if (textStatus == "success") {
                alert('Success');

                var color = $('#color');
                color.val(data.id);
                if (Object.keys(data).length === 1) {
                    data.name = inputColor.val();

                    appendNewOptionToSelect(client, data, data.id, data.name, true);
                }

                $('#add-new-brand-modal').modal('toggle');

                return false;
            }

            alert('fail');
        })
        .fail(function (data, textStatus, jqXHR) {
            console.log('fail big time');
        })
        .always(function (data, textStatus, jqXHR) {
            console.log('always');
        });
    });
});