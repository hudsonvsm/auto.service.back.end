$(document).ready(function() {
    $('#add-edit-popup-modal').on('show.bs.modal', function (e) {
        var jqXHR = $.get({ url: 'http://localhost/auto.service.back.end/AutomobileDataConnections' })
        .done(ajaxSuccess)
            .fail()
            .always();

        console.log(jqXHR);
    });

    function ajaxSuccess(data, textStatus, jqXHR) {
        var addEditElementData = $('#add-edit-element').data();

        $('#license-number').val(addEditElementData.licenseNumber);
        $('#year-of-production').val(addEditElementData.yearOfProduction);
        $('#engine-number').val(addEditElementData.engineNumber);
        $('#vin-number').val(addEditElementData.vinNumber);
        $('#engine-capacity').val(addEditElementData.engineCapacity);
        $('#description').val(addEditElementData.automobileDescription);

        var brandSelect = $('#brand');
        brandSelect.html('');
        $.each(data.brands, function (i, brand) {
            var selected = false;
            if(brand.name === addEditElementData.brand) selected = true;

            brandSelect.append($('<option>', {
                value: brand.id,
                text : brand.name,
                selected: selected
            }).data(brand));
        });

        var selectedBrand = brandSelect.find(":selected").data();

        var brandModelSelect = $('#brand-model');
        brandModelSelect.html('');
        $.each(data.brandModels, function (i, model) {
            var selected = false;
            if(model.name === addEditElementData.model) selected = true;

            var hidden = true;
            if(model.brandId === selectedBrand.id) hidden = false;

            brandModelSelect.append($('<option>', {
                value: model.id,
                text : model.name,
                selected: selected,
                hidden: hidden
            }).data(model));

        });

        var colorSelect = $('#color');
        colorSelect.html('');
        $.each(data.colors, function (i, color) {
            var selected = false;
            if(color.color === addEditElementData.color) selected = true;

            colorSelect.append($('<option>', {
                value: color.id,
                text : color.color,
                selected: selected
            }).data(color));
        });

        var clientSelect = $('#client');
        clientSelect.html('');
        $.each(data.clients, function (i, client) {
            var selected = false;

            client.fullName = client.firstName + ' ' + client.lastName;

            if(client.fullName === addEditElementData.ownerFullName) selected = true;

            clientSelect.append($('<option>', {
                value: client.id,
                text : client.fullName,
                selected: selected
            }).data(client));
        });
    }

    $(".row-element").on('click', ".edit-row", function (event) {
        $('#add-edit-element').data($(this).data());
    });

    $("#new_bidding").on('click', '.bid-decrease', function (event) {
        if($(this).hasClass('disable-bid')) {
            return false;
        }

        var bidding = $("#new_bidding");

        var priceStep = parseInt(bidding.find('.price-step').text());
        var currentPrice = parseInt(bidding.find('.current-price').text());

        var result = currentPrice - priceStep;
        bidding.find('.current-price').text(result);

        if ($("#new_bidding").data('minPrice') == result) {
            $(this).addClass('disable-bid');
            $('#bid-submit').addClass('disable-bid');
        }

        return false;
    })
    .on('click', '.bid-close', function (event) {
        $('.bid-decrease, #bid-submit').addClass('disable-bid');
    })
    .on('click', '.bid-increase', function (event) {
        var bidding = $("#new_bidding");

        var priceStep = parseInt(bidding.find('.price-step').text());
        var currentPrice = parseInt(bidding.find('.current-price').text());

        var result = currentPrice + priceStep;

        bidding.find('.current-price').text(result);

        if($('.bid-decrease').hasClass('disable-bid')) {
            $('.bid-decrease').removeClass('disable-bid');
        }

        if($('#bid-submit').hasClass('disable-bid')) {
            $('#bid-submit').removeClass('disable-bid');
        }

        return false;
    })
    .on('submit', function (event) {
        event.preventDefault();

        var bidPrice = parseInt($("#new_bidding .current-price").text());
        var $this = $(this);

        if ($('#bid-submit').hasClass('disable-bid')
            || $('.bid-decrease').hasClass('disable-bid')
            || (parseInt($this.data('minPrice')) == bidPrice)
        ) {
            return false;
        }

        $.ajax({
            url: window.location,
            data: {
                'bidPrice': bidPrice,
                'offerId': $this.data('offerId'),
            },
            dataType: 'json',
            type: 'PATCH',
            beforeSend: function() {
                $(".bidding-loading").show();
                $('.send-buttons').hide();
            },
            success: function (data, textStatus, jqXHR) {
                var offerElement = $('#' + $this.data('offerIndex'))

                // Success or all ready with highest bid
                if (data.success || data.errorCode == 1007) {
                    offerElement
                        .find('.offer-other-price')
                        .replaceWith(data.html);

                    $('#bidding-popup-modal').modal('hide');
                    return false;
                }

                // show error message
                $('.bidding-reason-fail')
                    .removeClass('hidden');
                $('.bidding-reason-fail span')
                    .text(data.reason);

                // exit if the error is in SOAP SERVER
                if (data.errorCode == 'SoapFault') {
                    return false;
                }

                offerElement
                    .attr('data-priceStart', data.newPrice)
                    .attr('data-winnerId', data.newBidWinnerId)
                    .find('.offer-other-price div span')
                    .text(data.newPrice + ' лв.');

                $this
                    .data('minPrice', data.newPrice)
                    .find('.current-price')
                    .text(data.newPrice);

                $('.bid-decrease, #bid-submit').addClass('disable-bid');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                window.location.reload();
            },
            complete: function(response) {
                $('.send-buttons').show();
                $(".bidding-loading").hide();
            }
        });

        return false;
    });
});