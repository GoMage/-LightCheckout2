define(
    [
        'mage/utils/wrapper',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function (wrapper, fullScreenLoader) {
        'use strict';

        return function (placeOrderServiceFunction) {
            var isEnable = false;
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
