function brandModelSelectorChanger (selectedBrand, brandModelSelect, selectedData) {
    appendNewOptionToSelect(brandModelSelect, {}, '', i18n['choose'], true);

    $.each(brandModelSelect.data(), function (i, model) {
        var selected = false;
        if(model.name === selectedData) selected = true;

        if(model.brandId === selectedBrand.id) {
            appendNewOptionToSelect(brandModelSelect, model, model.id, model.name, selected);
        }
    });
}

function appendNewOptionToSelect (selector, data, dataValue, dataText, selected) {
    selector.append($('<option>', {
        value: dataValue,
        text : dataText,
        selected: selected
    }).data(data));
}

function objectBuilderFromInputs($inputs) {
    var values = {};

    $inputs.each(function () {
        if (this.id !== '') {
            values[this.id] = $(this).val();
        }
    });

    return values;
}

function assignNewValuesToTableRowAndData(row, values, where) {
    $.each(values, function (i, value) {
        row.data(i, value);

        row.find(where + i).html(value);
    });
}

$(document).ready(function() {
    $(".row-element").on('click', ".delete-element", function (event) {
        if (!confirm(i18n['delete_confirmation'])) {
            return false;
        }

        $('.error-message').addClass('sr-only');
        var row = $(this).closest('.edit-row');

        var rowData = row.data();

        var url = CURRENT_URL;
        if (url.endsWith('Data')) url = url.slice(0, -'Data'.length);

        $.ajax({
            url: url + '/' + rowData.id,
            method: "DELETE",
            dataType: 'json',
            contentType: 'application/json; charset=utf-8'
        }).done(function (data, textStatus, jqXHR) {
            if (data.deleted && textStatus === "success") {
                row.remove();

                return false;
            }

            const errorMessage = i18n['error'].toUpperCase() + '!!! ' + data.error;

            $('.error-message')
                .removeClass('sr-only')
                .find('span')
                .text(errorMessage);

            alert(errorMessage);
        });
    });

    $('.error-message').on('click', '.close', function (event) {
        $('.error-message').addClass('sr-only');
    });
});