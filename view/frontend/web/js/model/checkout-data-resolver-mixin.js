define([
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/checkout-data',
    'Magento_Checkout/js/action/select-shipping-method',
    'underscore',
    'GoMage_LightCheckout/js/is-light-checkout-enable',
    'mage/utils/wrapper'
], function (
    quote,
    checkoutData,
    selectShippingMethodAction,
    _,
    isModuleEnable,
    wrapper
) {
    'use strict';

    return function (originalObject) {

        /**
         * @param {Object} ratesData
         */
        var isEnable = isModuleEnable.getIsLightCheckoutEnable;
        quote.shippingMethod.subscribe(function (newValue){
            console.log(newValue);
        });
        if (isEnable) {
            var configDefaultShippingRate = window.checkoutConfig.general.defaultShippingMethod;

            originalObject.resolveShippingRates = wrapper.wrapSuper(originalObject.resolveShippingRates, function (ratesData) {

                    if (configDefaultShippingRate){

                       var availableRate = _.find(ratesData, function (rate) {

                            return rate['carrier_code'] + '_' + rate['method_code'] === configDefaultShippingRate;
                        });

                        if (availableRate === undefined) {
                            if (ratesData.length !== 1 && quote.shippingMethod()) {
                                checkoutData.setSelectedShippingRate(null);
                            }
                        }else{
                            checkoutData.setSelectedShippingRate(availableRate['carrier_code'] + '_' + availableRate['method_code']);
                        }

                }

                this._super(ratesData);
            });
        }
        return originalObject;
    }
});
