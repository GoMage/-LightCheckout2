define(
    [
        'jquery',
        'underscore',
        'ko',
        'uiComponent',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Magento_Checkout/js/action/set-shipping-information',
        'GoMage_LightCheckout/js/action/save-additional-information'
    ],
    function (
        $,
        _,
        ko,
        Component,
        additionalValidators,
        setShippingInformation,
        saveAdditionalInformation
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
                     this.prepareToPlaceOrder().done(function () {
                         self._placeOrder();
                     });
                 }

                return this;
            },

            _placeOrder: function () {
                $(this.placeOrderPaymentMethodSelector).trigger('click');
            },

            prepareToPlaceOrder: function () {
                return $.when(setShippingInformation()).done(function () {
                    $.when(saveAdditionalInformation()).done(function () {
                        $("body").animate({scrollTop: 0}, "slow");
                    });
                });
            }
        });
    }
);
