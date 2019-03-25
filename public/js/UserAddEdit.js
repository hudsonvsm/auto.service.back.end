$(document).ready(function() {
    // User
    $(".row-element").on('click', ".edit-row", function (event) {
        if ($(event.target).hasClass('delete-element')) {
            return false;
        }
        $('#add-edit-user').data($(this).data());
    });

    $('#add-edit-popup-modal').off().on('show.bs.modal', function (e) {
        console.log($('#add-edit-user').data());
        if(jQuery.isEmptyObject($('#add-edit-user').data())) {
            $('#add-edit-popup-modal').find('input#client_id').prop({ 'disabled': false });
        }

        $(this).find('select#scope').val($('#add-edit-user').data('scope'));
        $(this).find('input#client_id').val($('#add-edit-user').data('clientId'));
    })
    .on('hide.bs.modal', function (e) {
        $('#add-edit-user').removeData();
        $(this).find('select#scope').val('');
        $(this).find('input#client_id').val('').prop({ 'disabled': true });
    });

    $("#add-edit-user").on('submit', function (event) {
        event.preventDefault();

        var $this = $(this);
        var values = objectBuilderFromInputs($('#add-edit-user').find(':input'));

        if (values.client_secret === '') {
             delete values.client_secret;
        }

        var method = 'POST';
        var id = '';
        if (typeof $this.data('clientId') !== 'undefined') {
            method = 'PATCH';
            id = '/' + $this.data('clientId');
            delete values.client_id;
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