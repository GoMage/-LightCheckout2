define(
    [
        'jquery',
        'ko',
        'uiComponent',
        'Magento_Checkout/js/action/set-shipping-information',
        'GoMage_LightCheckout/js/action/save-additional-information',
        'GoMage_LightCheckout/js/view/payment/place-order-action-allowed-state'
    ],
    function (
        $,
        ko,
        Component,
        setShippingInformation,
        saveAdditionalInformation,
        placeOrderActionAllowedState
    ) {
        "use strict";

        return Component.extend({
            defaults: {
                template: 'GoMage_LightCheckout/form/place-order'
            },
            placeOrderPaymentMethodSelector: '#co-payment-form .payment-method._active button.action.primary.checkout',

            placeOrder: function () {
                var self = this;
                placeOrderActionAllowedState.isPlaceOrderActionAllowed = false;

                this.prepareToPlaceOrder()
                    .done(function () {
                        self._placeOrder();
                    })
                    .always(function () {
                        placeOrderActionAllowedState.isPlaceOrderActionAllowed = true;
                    });

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
