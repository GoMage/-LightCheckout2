define([
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/checkout-data',
    'Magento_Checkout/js/action/select-shipping-method',
    'underscore',
    'GoMage_LightCheckout/js/is-light-checkout-enable'
], function (
    quote,
    checkoutData,
    selectShippingMethodAction,
    _,
    isModuleEnable
) {
    'use strict';

    return function (originalObject) {

        /**
         * @param {Object} ratesData
         */
        var isEnable = isModuleEnable.getIsLightCheckoutEnable;
        if (isEnable) {
            originalObject.resolveShippingRates = function (ratesData) {
                var selectedShippingRate = checkoutData.getSelectedShippingRate(),
                    availableRate = false;

                if (ratesData.length === 1 && !quote.shippingMethod()) {
                    //set shipping rate if we have only one available shipping rate
                    selectShippingMethodAction(ratesData[0]);

                    return;
                }

                if (quote.shippingMethod()) {
                    availableRate = _.find(ratesData, function (rate) {
                        return rate['carrier_code'] == quote.shippingMethod()['carrier_code'] && //eslint-disable-line
                            rate['method_code'] == quote.shippingMethod()['method_code']; //eslint-disable-line eqeqeq
                    });
                }
                if (availableRate === undefined) {
                    //set shipping rate if we have shipping rate from config which is not available
                    selectShippingMethodAction(ratesData[0]);

                    return;
                }
                if (!availableRate && selectedShippingRate) {
                    availableRate = _.find(ratesData, function (rate) {
                        return rate['carrier_code'] + '_' + rate['method_code'] === selectedShippingRate;
                    });
                }

                if (!availableRate && window.checkoutConfig.selectedShippingMethod) {
                    availableRate = _.find(ratesData, function (rate) {
                        var selectedShippingMethod = window.checkoutConfig.selectedShippingMethod;

                        return rate['carrier_code'] == selectedShippingMethod['carrier_code'] && //eslint-disable-line
                            rate['method_code'] == selectedShippingMethod['method_code']; //eslint-disable-line eqeqeq
                    });
                }

                //Unset selected shipping method if not available
                if (!availableRate) {
                    selectShippingMethodAction(null);
                } else {
                    selectShippingMethodAction(availableRate);
                }
            }
        }
        return originalObject;
    }
});
