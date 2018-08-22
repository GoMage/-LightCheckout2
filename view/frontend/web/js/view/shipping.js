define(
    [
        'Magento_Checkout/js/view/shipping',
        'Magento_Checkout/js/model/quote'
    ],
    function (Component,
              quote) {
        'use strict';

        return Component.extend({
            initObservable: function () {
                this._super()
                    .observe({
                        isAddressSameAsShipping: false
                    });

                quote.billingAddress.subscribe(function (newAddress) {
                    if (quote.isVirtual()) {
                        this.isAddressSameAsShipping(false);
                    } else {
                        this.isAddressSameAsShipping(
                            newAddress != null &&
                            newAddress.getCacheKey() == quote.shippingAddress().getCacheKey()
                        );
                    }

                }, this);

                return this;
            }
        });
    }
);
