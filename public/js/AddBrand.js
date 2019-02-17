$(document).ready(function() {
    // brand
    $('#add-new-brand-modal').on('hide.bs.modal', function (e) {
        $(this).find('input#name').val('');
    });

    $("#add-new-model").on('submit', function (event) {
        event.preventDefault();

        var $this = $(this);
        var inputName = $this.find('input#name');

        $.post({
            url: URL + '/AutomobileBrand',
            data: {
                name: inputName.val()
            }
        }, 'json').done(function (data, textStatus, jqXHR) {
            if (textStatus === "success") {
                alert(i18n[textStatus].toUpperCase());

                var brand = $('#brand');
                brand.val(data.id);
                if (Object.keys(data).length === 1) {
                    data.name = inputName.val();

                    appendNewOptionToSelect(client, data, data.id, data.name, true);
                }

                $('#add-new-brand-modal').modal('toggle');

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