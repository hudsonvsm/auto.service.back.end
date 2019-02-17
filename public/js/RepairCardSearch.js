$(document).ready(function() {
    $.getJSON(URL + '/Automobile', function (data, textStatus, jqXHR) {
        var select = $('#search-form').find('#license_number');
        var selectedId = select.data('selected');

        select.html('');

        appendNewOptionToSelect(select, {}, '', i18n.choose, true);

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
    .on('change', '#unfinished', function (event) {
        //var disabled = $(this).prop('checked') === '' ? true : false;

        $('#end_date_before').prop('disabled', $(this).prop('checked'));
    })
    .on('change', '#end_date_before', function (event) {
        var disabled = $(this).val() !== '' ? true : false;

        $('#unfinished').prop('disabled', disabled);
    });
});