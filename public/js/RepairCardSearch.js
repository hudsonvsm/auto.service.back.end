$(document).ready(function() {
    $.getJSON(URL + '/Automobile', function (data, textStatus, jqXHR) {
        var select = $('#search-form').find('#license_number');
        var selectedId = select.data('selected');

        select.html('');

        appendNewOptionToSelect(select, {}, '', 'Избери', true);

        $.each(data.data, function (i, automobile) {
            var selected = false;
            if(automobile.licenseNumber == selectedId) selected = true;

            appendNewOptionToSelect(select, automobile, automobile.licenseNumber, automobile.licenseNumber, selected);
        });
    });

    $('#search-form').on('submit', function (event) {
        var inputs = $(this).find('.search-param');

        var allValuesAreEmpty = true;

        $.each(inputs, function (i, value) {
            if ($(value).is(':checkbox') ) {
                if ($(value).is(':checked')) allValuesAreEmpty = false;
            } else if ( ! ($(value).val().length == 0)) {
                allValuesAreEmpty = false
            }
        });

        if (allValuesAreEmpty) {
            event.preventDefault();
            location.replace(URL + '/RepairCardData');
        }
    })
});