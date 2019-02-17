$(document).ready(function() {
    // client
    $('#add-new-client-modal').on('hide.bs.modal', function (e) {
        $(this).find('input#first-name').val('');
        $(this).find('input#last-name').val('');
        $(this).find('input#phone-number').val('');
    });

    $("#add-new-client").on('submit', function (event) {
        event.preventDefault();

        var $this = $(this);
        var inputFirstName = $this.find('input#first-name');
        var inputLastName = $this.find('input#last-name');
        var inputPhoneNumber = $this.find('input#phone-number');

        $.post({
            url: URL + '/Client',
            data: {
                first_name: inputFirstName.val(),
                last_name: inputLastName.val(),
                phone_number: inputPhoneNumber.val()
            }
        }, 'json').done(function (data, textStatus, jqXHR) {
            if (textStatus === "success") {
                alert(i18n[textStatus].toUpperCase());

                var client = $('#owner_id');
                client.val(data.id);
                if (Object.keys(data).length === 1) {
                    data.fullName = inputFirstName.val() + ' ' + inputLastName.val();
                    data.firstName = inputFirstName.val();
                    data.lastName = inputLastName.val();
                    data.phoneNumber = inputPhoneNumber.val();

                    appendNewOptionToSelect(client, data, data.id, data.fullName, true);
                }

                $('#add-new-client-modal').modal('toggle');

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