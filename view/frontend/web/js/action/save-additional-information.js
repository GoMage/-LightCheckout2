define(
    [
        'jquery',
        'Magento_Checkout/js/model/quote',
        'mage/storage',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader',
        'GoMage_LightCheckout/js/model/resource-url-manager'
    ],
    function (
        $,
        quote,
        storage,
        errorProcessor,
        fullScreenLoader,
        resourceUrlManager
    ) {
        'use strict';

        return function () {
            var serviceUrl = resourceUrlManager.getUrlForSaveAdditionalInformation(),
                passwordSelector = $('#account-password'),
                isCheckboxChecked = $('input[name=create-account-checkbox]').is(":checked"),
                isPasswordForLoginVisible = $('.form-login #customer-email-fieldset #customer-password').is(":visible"),
                payload = {
                    additionInformation: {password: passwordSelector.val()}
                };

            if (!isCheckboxChecked || isPasswordForLoginVisible) {
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
