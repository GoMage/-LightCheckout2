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

    /**
     * Purpose of this mixin is adding additional logic to select default shipping method form LightCheckout configuration
     * when checkout/cart page is initialized or ratesData is updated (f.e. when address is changed)
     */
    return function (originalObject) {

        var isEnable = isModuleEnable.getIsLightCheckoutEnable;

        if (isEnable) {
            originalObject.resolveShippingRates = wrapper.wrapSuper(originalObject.resolveShippingRates, function (ratesData) {
                var configDefaultShippingRate = window.checkoutConfig.general.defaultShippingMethod,
                    selectedShippingRate = checkoutData.getSelectedShippingRate();

                if (ratesData.length === 1 && quote.shippingMethod()) {
                    selectShippingMethodAction(null);
                }

                if (ratesData.length > 1 && quote.shippingMethod() && quote.shippingMethod()['carrier_code'] + '_' +
                    quote.shippingMethod()['method_code'] !== selectedShippingRate) {
                    selectShippingMethodAction(selectedShippingRate);
                }

                // When checkout/cart page is load first time and localStorage's selected shipping method is empty
                if (configDefaultShippingRate && !selectedShippingRate) {
                   var availableRate = _.find(ratesData, function (rate) {
                        // Find the default shipping method from LightCheckout configuration in the available shipping methods
                        return rate['carrier_code'] + '_' + rate['method_code'] === configDefaultShippingRate;
                   });

                   if (availableRate === undefined) {
                       checkoutData.setSelectedShippingRate(null);
                   } else {
                       checkoutData.setSelectedShippingRate(availableRate['carrier_code'] + '_' + availableRate['method_code']);
                   }
                }

                this._super(ratesData);
            });
        }

        return originalObject;
    }
});
