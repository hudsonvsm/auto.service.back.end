function brandModelSelectorChanger (selectedBrand, brandModelSelect, selectedData) {
    appendNewOptionToSelect(brandModelSelect, {}, '', 'Избери', true);

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
        values[this.id] = $(this).val();
    });

    return values;
}