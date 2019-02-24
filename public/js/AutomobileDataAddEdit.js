$(document).ready(function() {
    $(".row-element").on('click', ".edit-row", function (event) {
        if ($(event.target).hasClass('delete-element')) {
            return false;
        }

        $('#add-edit-automobile').data($(this).data());
    });

    $('#add-edit-popup-modal').off().on('show.bs.modal', function (e) {
        $.getJSON({ url: URL + '/AutomobileDataConnections' }).done(function (data, textStatus, jqXHR) {
            var addEditElementData = $('#add-edit-automobile').data();

            $('#license_number').val(addEditElementData.licenseNumber);
            $('#year_of_production').val(addEditElementData.yearOfProduction);
            $('#engine_number').val(addEditElementData.engineNumber);
            $('#vin_number').val(addEditElementData.vinNumber);
            $('#engine_capacity').val(addEditElementData.engineCapacity);
            $('#description').val(addEditElementData.automobileDescription);

            var brandSelect = $('#brand_id');
            brandSelect.html('');
            appendNewOptionToSelect(brandSelect, {}, '', i18n['choose'], true);
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
            appendNewOptionToSelect(colorSelect, {}, '', i18n['choose'], true);
            $.each(data.colors, function (i, color) {
                var selected = false;
                if(color.name === addEditElementData.color) selected = true;

                appendNewOptionToSelect(colorSelect, color, color.id, color.name, selected);
            });

            var clientSelect = $('#owner_id');
            clientSelect.html('');
            appendNewOptionToSelect(clientSelect, {}, '', i18n['choose'], true);
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
        $('.error').removeClass('error');
    });

    $("#add-edit-automobile").off().on('submit', function (event) {
        event.preventDefault();

        $('.error').removeClass('error');

        var $this = $(this);
        var values = objectBuilderFromInputs($('#add-edit-automobile').find(':input'));
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
            if (typeof data.error === 'undefined' && textStatus === "success") {
                alert(i18n[textStatus].toUpperCase());

                if (method === 'PATCH') {
                    var row = $('.edit-row[data-id="' + $this.data('id') + '"]');

                    assignNewValuesToTableRowAndData(row, values, 'td.');
                }

                $('#add-edit-popup-modal').modal('toggle');

                location.reload();
                return false;
            }

            $('#' + data.error).addClass('error');
            $('label[for=' + data.error + ']').addClass('error');

            alert(i18n['error'].toUpperCase() + '!!!');
        })
        .fail(function (data, textStatus, jqXHR) {
            console.log('fail big time');
        })
        .always(function (data, textStatus, jqXHR) {
            console.log('always');
        });
    });

    $('#brand_id').on('change', function (event) {
        var selectedBrand = $(this).find(":selected").data();

        var brandModelSelect = $('#model_id');
        brandModelSelect.html('');
        brandModelSelectorChanger(selectedBrand, brandModelSelect, '');
    })
});