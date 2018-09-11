define(
    [
        'jquery',
        'Magento_Checkout/js/model/quote',
        'mage/storage',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader',
        'GoMage_LightCheckout/js/model/resource-url-manager',
        'underscore'
    ],
    function (
        $,
        quote,
        storage,
        errorProcessor,
        fullScreenLoader,
        resourceUrlManager,
        _
    ) {
        'use strict';

        return function () {
            var serviceUrl = resourceUrlManager.getUrlForSaveAdditionalInformation(),
                passwordVal = $('#account-password').val(),
                isCheckboxChecked = $('input[name=create-account-checkbox]').is(":checked"),
                isPasswordForLoginVisible = $('.form-login #customer-email-fieldset #customer-password').is(":visible"),
                deliveryDateVal = $('#delivery-date input').val(),
                deliveryTimeVal = $('#delivery-date select option:selected').text(),
                payload = {
                    additionInformation: {}
                };

            if (isCheckboxChecked && !isPasswordForLoginVisible) {
                payload.additionInformation.password = passwordVal;
            }

            if (deliveryDateVal) {
                payload.additionInformation.deliveryDate = deliveryDateVal;
                payload.additionInformation.deliveryDateTime = deliveryTimeVal;
            }

            if (_.isEmpty(payload.additionInformation)) {
                return;
            }

            fullScreenLoader.startLoader();

            return storage.post(
                serviceUrl,
                JSON.stringify(payload)
            ).fail(
                function (response) {
                    errorProcessor.process(response);
                }
            ).always(function () {
                fullScreenLoader.stopLoader();
            });
        };
    }
);
