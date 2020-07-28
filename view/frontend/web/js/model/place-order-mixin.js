define(
    [
        'mage/utils/wrapper',
        'Magento_Checkout/js/model/full-screen-loader',
        'GoMage_LightCheckout/js/is-light-checkout-enable'
    ],
    function (wrapper, fullScreenLoader, isModuleEnable) {
        'use strict';

        return function (placeOrderServiceFunction) {
            var isEnable = isModuleEnable.getIsLightCheckoutEnable;
            if (isEnable) {
                return wrapper.wrap(placeOrderServiceFunction, function (
                    originalPlaceOrderServiceFunction, serviceUrl, payload, messageContainer
                ) {
                    return originalPlaceOrderServiceFunction(serviceUrl, payload, messageContainer).fail(
                        function (response) {
                            fullScreenLoader.stopLoader();
                        }
                    );
                });
            }
            return placeOrderServiceFunction
        };
    }
);
