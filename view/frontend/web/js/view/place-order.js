define(
    [
        'jquery',
        'underscore',
        'ko',
        'uiComponent',
        'Magento_Checkout/js/model/payment/additional-validators',
        'GoMage_LightCheckout/js/action/save-additional-information',
        'GoMage_LightCheckout/js/light-checkout-data',
        'Magento_Checkout/js/action/select-shipping-address',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/action/create-shipping-address',
        'Magento_Checkout/js/checkout-data'
    ],
    function (
        $,
        _,
        ko,
        Component,
        additionalValidators,
        saveAdditionalInformation,
        lightCheckoutData,
        selectShippingAddress,
        quote,
        createShippingAddress,
        checkoutData
    ) {
        "use strict";

        return Component.extend({
            defaults: {
                template: 'GoMage_LightCheckout/form/place-order',
                isPlaceOrderButtonClicked: ko.observable(false)
            },
            placeOrderPaymentMethodSelector: '#co-payment-form .payment-method._active button.action.primary.checkout',

            placeOrder: function () {
                var self = this;
                self.isPlaceOrderButtonClicked(false); // Save shipping address only 1 time on validation step

                // if (lightCheckoutData.getIsAddressSameAsShipping()) {
                //     selectShippingAddress(quote.billingAddress());
                // } else {
                //     var addressData = checkoutData.getShippingAddressFromData();
                //     selectShippingAddress(createShippingAddress(addressData));
                // }

                 if (additionalValidators.validate()) { // попробовать убрать эти условия
                     self.isPlaceOrderButtonClicked(true);
                     this.prepareToPlaceOrder().done(function () {
                         self._placeOrder();
                     }).fail(function () {
                         self.isPlaceOrderButtonClicked(false);
                     });
                 } else {
                     self.isPlaceOrderButtonClicked(false);
                 }

                return this;
            },

            _placeOrder: function () {
                $(this.placeOrderPaymentMethodSelector).trigger('click');
            },

            prepareToPlaceOrder: function () {
                return $.when(saveAdditionalInformation()).done(function () {
                    $("body").animate({scrollTop: 0}, "slow");
                });
            }
        });
    }
);
