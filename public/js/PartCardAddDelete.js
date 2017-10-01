$(document).ready(function() {
    $("#add-part-for-card").off().on('submit', function (event) {
        event.preventDefault();

        var values = objectBuilderFromInputs($('#add-part-for-card :input'));

        var partData = $('#automobile_part_id :selected').data();

        if (!values.automobile_part_id || !values.repair_card_id) {
            return false;
        }

        $.ajax({
            url: URL + '/AutomobilePartRepairCard',
            method: 'POST',
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            data: JSON.stringify(values)
        }, 'json').done(function (data, textStatus, jqXHR) {
            if (textStatus == "success") {
                alert('Success');

                partData.id = data.id;
                partData.partPrice = partData.price;

                delete partData.price;

                var tableRow = $('<tr></tr>');

                tableRow.data(partData).addClass("edit-row");

                $.each(partData, function (i, value) {
                    if (i !== 'id') {
                        tableRow.append($('<td></td>')
                            .addClass(i)
                            .html(value)
                        );
                    }
                });

                tableRow.append(
                    $('<td class="delete"><input class="btn btn-outline-danger delete-element" type="button" value="Изтрий"></td>')
                );

                $(".part-card-row").append(tableRow);

                var totalPrice = $('#total_price');

                var result = parseFloat(totalPrice.val()) + parseFloat(partData.partPrice);

                totalPrice.val(result.toFixed(2));

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

    $(".part-card-row").on('click', ".delete-element", function (event) {
        if (!confirm("Наистина ли искате да изтриете този елемент")) {
            return false;
        }

        var row = $(this).closest('.edit-row');

        var rowData = row.data();
        var url = URL + '/AutomobilePartRepairCard/' + rowData.id;

        $.ajax({
            url: url,
            method: "DELETE",
            dataType: 'json',
            contentType: 'application/json; charset=utf-8'
        }).done(function (data, textStatus, jqXHR) {
            if (data.deleted && textStatus == "success") {
                var totalPrice = $('#total_price');

                var total = parseFloat(totalPrice.val());
                var partPriceToDelete = parseFloat(rowData.partPrice);

                var result = total - partPriceToDelete;

                totalPrice.val(result.toFixed(2));

                row.remove();

                return false;
            }

            alert('fail');
        });
    });
});