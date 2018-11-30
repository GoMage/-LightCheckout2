
define(
    [
        'jquery',
        'Magento_Checkout/js/model/quote',
        'GoMage_LightCheckout/js/model/resource-url-manager',
        'mage/storage',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader',
        'uiRegistry',
        'Magento_Customer/js/customer-data'
    ],
    function (
        $,
        quote,
        resourceUrlManager,
        storage,
        errorProcessor,
        fullScreenLoader,
        uiRegistry,
        customerData
    ) {
        'use strict';

        return function (postcode, parentScope) {
            var payload = {
                postcode: postcode
            };

            fullScreenLoader.startLoader();

            return storage.post(
                resourceUrlManager.getUrlForGetAddressByPostCode(),
                JSON.stringify(payload)
            ).done(
                function (response) {
                    if (response.redirect_url) {
                        customerData.invalidate(['cart']);
                        window.location.href = response.redirect_url;
                        return;
                    }

                    if (response.city) {
                        uiRegistry.get(parentScope + '.city').value(response.city);
                    }
                    if (response.country_id) {
                        uiRegistry.get(parentScope + '.country_id').value(response.country_id);
                    }
                    if (response.region_id) {
                        uiRegistry.get(parentScope + '.region_id').value(response.region_id);
                    }

                    if (response.enable_fields === true || response.enable_fields === false) {
                        uiRegistry.get(parentScope + '.city').disabled(!response.enable_fields);
                        uiRegistry.get(parentScope + '.country_id').disabled(!response.enable_fields);
                        uiRegistry.get(parentScope + '.region_id').disabled(!response.enable_fields);
                    }
                }
            ).fail(
                function (response) {
                    errorProcessor.process(response);
                }
            ).always(
                function () {
                    fullScreenLoader.stopLoader();
                }
            );
        };
    }
);
