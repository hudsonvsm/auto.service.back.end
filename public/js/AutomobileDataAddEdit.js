$(document).ready(function() {
    $('#add-edit-popup-modal').off().on('show.bs.modal', function (e) {
        $.getJSON({ url: URL + '/AutomobileDataConnections' }).done(function (data, textStatus, jqXHR) {
            var addEditElementData = $('#add-edit-automobile').data();

            $('#license_number').val(addEditElementData.licenseNumber);
            $('#year_of_production').val(addEditElementData.yearOfProduction);
            $('#engine_number').val(addEditElementData.engineNumber);
            $('#vin_number').val(addEditElementData.vinNumber);
            $('#engine_capacity').val(addEditElementData.engineCapacity);
            $('#description').val(addEditElementData.automobileDescription);

            var brandSelect = $('#brand');
            brandSelect.html('');
            appendNewOptionToSelect(brandSelect, {}, '', 'Избери', true);
            $.each(data.brands, function (i, brand) {
                var selected = false;
                if(brand.name === addEditElementData.brand) selected = true;

                appendNewOptionToSelect(brandSelect, brand, brand.id, brand.name, selected);
            });

            var selectedBrand = brandSelect.find(":selected").data();

            var brandModelSelect = $('#model_id');
            brandModelSelect.data(data.brandModels);
            brandModelSelect.html('');
            brandModelSelectorChanger(selectedBrand, brandModelSelect, addEditElementData.model);

            var colorSelect = $('#color_id');
            colorSelect.html('');
            appendNewOptionToSelect(colorSelect, {}, '', 'Избери', true);
            $.each(data.colors, function (i, color) {
                var selected = false;
                if(color.name === addEditElementData.color) selected = true;

                appendNewOptionToSelect(colorSelect, color, color.id, color.name, selected);
            });

            var clientSelect = $('#owner_id');
            clientSelect.html('');
            appendNewOptionToSelect(clientSelect, {}, '', 'Избери', true);
            $.each(data.clients, function (i, client) {
                var selected = false;

                client.fullName = client.firstName + ' ' + client.lastName;

                if(client.fullName === addEditElementData.ownerFullName) selected = true;

                appendNewOptionToSelect(clientSelect, client, client.id, client.fullName, selected);
            });
        });
    }).on('hide.bs.modal', function (event) {
        $('#license_number').val('');
        $('#year_of_production').val('');
        $('#engine_number').val('');
        $('#vin_number').val('');
        $('#engine_capacity').val('');
        $('#description').val('');
        $('#add-edit-automobile').removeData();
    });

    $("#add-edit-automobile").off().on('submit', function (event) {
        event.preventDefault();

        var $this = $(this);
        var values = objectBuilderFromInputs($('#add-edit-automobile :input'));
        var method = 'POST';
        var id = '';
        if (typeof $this.data('id') !== 'undefined') {
            method = 'PATCH';
            id = '/' + $this.data('id');
        }

        $.ajax({
            url: URL + '/Automobile' + id,
            method: method,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            data: JSON.stringify(values)
        }, 'json').done(function (data, textStatus, jqXHR) {
            if (textStatus == "success") {
                alert('Success');

                if (method === 'PATCH') {
                    var row = $('.edit-row[data-id="' + $this.data('id') + '"]');

                    assignNewValuesToTableRowAndData(row, values, 'td.');
                }

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

    $(".row-element").on('click', ".edit-row", function (event) {
        $('#add-edit-automobile').data($(this).data());
    });

    $('#brand').on('change', function (event) {
        var selectedBrand = $(this).find(":selected").data();

        var brandModelSelect = $('#model_id');
        brandModelSelect.html('');
        brandModelSelectorChanger(selectedBrand, brandModelSelect, '');
    })
});