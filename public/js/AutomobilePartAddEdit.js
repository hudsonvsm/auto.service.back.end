$(document).ready(function() {
    // automobile part
    $(".row-element").on('click', ".edit-row", function (event) {
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
        var values = objectBuilderFromInputs($('#add-edit-automobile-part :input'));

        var method = 'POST';
        var id = '';
        if (typeof $this.data('id') !== 'undefined') {
            method = 'PATCH';
            id = '/' + $this.data('id');
        }

        $.ajax({
            url: URL + '/AutomobilePart' + id,
            method: method,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            data: JSON.stringify(values)
        }).done(function (data, textStatus, jqXHR) {
            if (textStatus == "success") {
                alert('Success');

                if (method === 'POST') {
                    location.reload();
                }

                console.log('.edit-row[data-id="' + $this.data('id') + '"]');

                var row = $('.edit-row[data-id="' + $this.data('id') + '"]');

                row.data('name', values.name);
                row.data('price', values.price);

                var tableData = row.find('td');

                tableData.eq(0).html(values.name);
                tableData.eq(1).html(values.price);

                $('#add-edit-popup-modal').modal('toggle');

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