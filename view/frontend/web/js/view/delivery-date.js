define(
    [
        'ko',
        'uiComponent',
        'Magento_Checkout/js/model/quote'
    ],
    function (ko,
              Component,
              quote) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'GoMage_LightCheckout/form/delivery-date',
                useForShippingMethod: ko.observable(true)
            },
            deliveryDateText: window.checkoutConfig.deliveryDate.deliveryDateText,
            displayDeliveryDateText: window.checkoutConfig.deliveryDate.displayDeliveryDateText,

            initialize: function () {
                var self = this;
                this._super();

                quote.shippingMethod.subscribe(function (newValue) {
                    var newShipping  = newValue['carrier_code'] + '_' + newValue['method_code'];
                    if (window.checkoutConfig.deliveryDate.shippingMethods.indexOf(newShipping) === -1) {
                        self.useForShippingMethod(false);
                    } else {
                        self.useForShippingMethod(true);
                    }
                }, this);
            }
        });
    }
);
