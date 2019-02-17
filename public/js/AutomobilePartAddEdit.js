$(document).ready(function() {
    // automobile part
    $(".row-element").on('click', ".edit-row", function (event) {
        if ($(event.target).hasClass('delete-element')) {
            return false;
        }

        $('#add-edit-automobile-part').data($(this).data());
    });

    $('#add-edit-popup-modal').off().on('show.bs.modal', function (e) {
        $(this).find('input#name').val($('#add-edit-automobile-part').data('name'));
        $(this).find('input#price').val($('#add-edit-automobile-part').data('price'));
    })
    .on('hide.bs.modal', function (e) {
        $('#add-edit-automobile-part').removeData();
        $(this).find('input#name').val('');
        $(this).find('input#price').val('');
    });

    $("#add-edit-automobile-part").on('submit', function (event) {
        event.preventDefault();

        var $this = $(this);
        var values = objectBuilderFromInputs($('#add-edit-automobile-part').find(':input'));

        var method = 'POST';
        var id = '';
        if (typeof $this.data('id') !== 'undefined') {
            method = 'PATCH';
            id = '/' + $this.data('id');
        }

        $.ajax({
            url: CURRENT_URL + id,
            method: method,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            data: JSON.stringify(values)
        }).done(function (data, textStatus, jqXHR) {
            if (textStatus === "success") {
                alert(i18n[textStatus].toUpperCase());

                if (method === 'PATCH') {
                    var row = $('.edit-row[data-id="' + $this.data('id') + '"]');

                    assignNewValuesToTableRowAndData(row, values, 'td.');

                    $('#add-edit-popup-modal').modal('toggle');

                    return false;
                }

                location.reload();

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