define(
    [
        'jquery',
        'underscore',
        'ko',
        'uiComponent',
        'Magento_Checkout/js/model/payment/additional-validators'
    ],
    function (
        $,
        _,
        ko,
        Component,
        additionalValidators
    ) {
        "use strict";

        return Component.extend({
            defaults: {
                template: 'GoMage_LightCheckout/form/place-order'
            },
            placeOrderPaymentMethodSelector: '#co-payment-form .payment-method._active button.action.primary.checkout',

            placeOrder: function () {
                var self = this;
                if (additionalValidators.validate()) {
                    self._placeOrder();
                }

                return this;
            },

            _placeOrder: function () {
                $(this.placeOrderPaymentMethodSelector).trigger('click');
            }
        });
    }
);
