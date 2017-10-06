$(document).ready(function() {
    $(".row-element").on('click', ".edit-row", function (event) {
        if ($(event.target).hasClass('delete-element')) {
            return false;
        }

        $('#add-edit-repair-card').data($(this).data());
    });

    $('#add-edit-popup-modal').off().on('show.bs.modal', function (e) {
        var $this = $(this);
        var addEditElementData = $('#add-edit-repair-card').data();

        if (Object.keys(addEditElementData).length !== 0) {
            $('#repair_card_id').val(addEditElementData.id);
            $('#acceptance_date').val(addEditElementData.acceptanceDate.replace(' ', 'T'));
            $('#start_date').val(addEditElementData.startDate.replace(' ', 'T'));
            $('#end_date').val(addEditElementData.endDate.replace(' ', 'T'));
            $('#total_price').val(addEditElementData.totalPrice);
            $('#description').val(addEditElementData.cardDescription);
            $('#number').val(addEditElementData.number);

            var search = 'search[repair_card_id]='+ addEditElementData.id;

            $.get(URL + '/AutomobilePartRepairCard?' + search, function (data, textStatus, jqXHR) {
                $('#part-card-container').html(data);
            });
        } else {
            $('#add-part-for-card').hide();
            $('#add-part-for-card-header').hide();
        }

        $.getJSON(URL + '/Worker', function (data, textStatus, jqXHR) {
            var select = $this.find('#worker_id');
            select.html('');
            appendNewOptionToSelect(select, {}, '', 'Избери', true);
            $.each(data.data, function (i, worker) {
                var selected = false;

                worker.fullName = worker.firstName + ' ' + worker.lastName;

                if(worker.fullName === addEditElementData.workerFullName) selected = true;

                appendNewOptionToSelect(select, worker, worker.id, worker.fullName, selected);
            });
        });

        $.getJSON(URL + '/Automobile', function (data, textStatus, jqXHR) {
            var select = $this.find('#automobile_id');
            select.html('');
            appendNewOptionToSelect(select, {}, '', 'Избери', true);
            $.each(data.data, function (i, automobile) {
                var selected = false;
                if(automobile.licenseNumber === addEditElementData.licenseNumber) selected = true;

                appendNewOptionToSelect(select, automobile, automobile.id, automobile.licenseNumber, selected);
            });
        });

        $.getJSON(URL + '/AutomobilePart?returnDataType=json', function (data, textStatus, jqXHR) {
            var select = $this.find('#automobile_part_id');
            select.html('');
            appendNewOptionToSelect(select, {}, '', 'Избери', true);
            $.each(data.data, function (i, part) {
                appendNewOptionToSelect(select, part, part.id, part.name + ': ' + part.price + ' лв.', false);
            });
        });
    }).on('hide.bs.modal', function (event) {
        var addEditElementData = $('#add-edit-repair-card').data();

        if (Object.keys(addEditElementData).length !== 0) {
            var row = $('.edit-row[data-id=' + addEditElementData.id + ']');

            var totalPrice = $('#total_price').val();

            row.data('totalPrice', totalPrice);
            row.find('.totalPrice').html(totalPrice);
        }

        $('#part-card-container').html('');
        $('#number').val('');
        $('#acceptance_date').val('');
        $('#start_date').val('');
        $('#end_date').val('');
        $('#description').val('');
        $('#repair_card_id').val('')
        $('#total_price').val('');
        $('#add-edit-repair-card').removeData();
    });

    $("#add-edit-repair-card").off().on('submit', function (event) {
        event.preventDefault();

        var $this = $(this);
        var values = objectBuilderFromInputs($('#add-edit-repair-card :input'));
        var method = 'POST';
        var id = '';
        if (typeof $this.data('id') !== 'undefined') {
            method = 'PATCH';
            id = '/' + $this.data('id');
        }

        $.ajax({
            url: URL + '/RepairCard' + id,
            method: method,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            data: JSON.stringify(values)
        }, 'json').done(function (data, textStatus, jqXHR) {
            if (textStatus !== "success") {
                alert('fail');
            }
            alert('Success');

            if (method === 'PATCH') {
                if ($this.data('afterPost')) location.reload();

                var row = $('.edit-row[data-id="' + $this.data('id') + '"]');

                assignNewValuesToTableRowAndData(row, values, 'td.');

                $('#add-edit-popup-modal').modal('toggle');

                return false;
            }

            $('#add-part-for-card').show();
            $('#add-part-for-card-header').show();
            $('#total_price').val('0.00');
            $('#repair_card_id').val(data.id);

            var search = 'search[repair_card_id]='+ data.id;

            $.get(URL + '/AutomobilePartRepairCard?' + search, function (html, textStatus, jqXHR) {
                $('#part-card-container').html(html);
            });

            $.getJSON(URL + '/RepairCard/' + data.id, function( json ) {
                $this.data(json);
                $this.data('afterPost', true);
                $('#number').val(json.number);
            });
        })
        .fail(function (data, textStatus, jqXHR) {
            console.log('fail big time');
        })
        .always(function (data, textStatus, jqXHR) {
            console.log('always');
        });
    });
});