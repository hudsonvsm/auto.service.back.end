$(document).ready(function() {
    // brand model
    $('#add-new-brand-model-modal')
        .on('show.bs.modal', function (e) {
            var selectedBrand = $("#brand_id").find(":selected").data();

            $("#add-brand-for-model").val(selectedBrand.name).data(selectedBrand);
        })
        .on('hide.bs.modal', function (e) {
            $(this).find("#add-brand-for-model").val('').data({});

            $(this).find('input#name').val('');
        });

    $("#add-new-brand-to-model").on('submit', function (event) {
        event.preventDefault();

        var $this = $(this);
        var brandData = $this.find('input#add-brand-for-model').data();
        var inputName = $this.find('input#name');

        $.post({
            url: URL + '/AutomobileBrandModel',
            data: {
                brand_id: brandData.id,
                name: inputName.val()
            }
        }, 'json').done(function (data, textStatus, jqXHR) {
            if (textStatus === "success") {
                alert(i18n[textStatus].toUpperCase());

                var brandModel = $('#model_id');
                brandModel.val(data.id);
                if (Object.keys(data).length === 1) {
                    data.name = inputName.val();
                    data.brand_id = brandData.id;

                    var brandModelData = brandModel.data();
                    brandModelData[Object.keys(brandModelData).length] = data;
                    brandModel.data(brandModelData);

                    appendNewOptionToSelect(brandModel, data, data.id, data.name, true);
                }

                $('#add-new-brand-model-modal').modal('toggle');

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