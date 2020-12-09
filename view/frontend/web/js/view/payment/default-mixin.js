define(
    [
        'uiRegistry',
        'underscore',
        'GoMage_LightCheckout/js/is-light-checkout-enable',
        'GoMage_LightCheckout/js/view/payment/place-order-action-allowed-state'
    ],
    function (uiRegistry, _,isModuleEnable, placeOrderActionAllowedState) {
        'use strict';

        return function (paymentDefault) {
            var isEnable = isModuleEnable.getIsLightCheckoutEnable;
            if (isEnable) {
                return paymentDefault.extend({
                    initChildren: function () {
                        this.messageContainer = uiRegistry.get('checkout.errors').messageContainer;

                        return this;
                    },

                    initialize: function () {
                        this.isPlaceOrderActionAllowed.subscribe(function (value) {
                            placeOrderActionAllowedState.isPlaceOrderActionAllowed = value;
                        });

                        return this._super();
                    }
                });
            }
            return paymentDefault;
        }
    }
);
