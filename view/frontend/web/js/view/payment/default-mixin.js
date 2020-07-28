define(
    [
        'uiRegistry',
        'underscore',
        'GoMage_LightCheckout/js/is-light-checkout-enable'
    ],
    function (uiRegistry, _,isModuleEnable) {
        'use strict';

        return function (paymentDefault) {
            var isEnable = isModuleEnable.getIsLightCheckoutEnable;
            if (isEnable) {
                return paymentDefault.extend({
                    initChildren: function () {
                        this.messageContainer = uiRegistry.get('checkout.errors').messageContainer;

                        return this;
                    }
                });
            }
            return paymentDefault;
        }
    });
