define(
    [
        'jquery',
        'Magento_Checkout/js/view/billing-address',
        'uiRegistry',
        'Magento_Checkout/js/checkout-data'
    ],
    function (
        $,
        Component,
        uiRegistry,
        checkoutData
    ) {
        'use strict';

        return Component.extend({
            initialize: function () {
                this._super();

                uiRegistry.async('checkoutProvider')(function (checkoutProvider) {
                    var billingAddressData = checkoutData.getBillingAddressFromData();

                    if (billingAddressData) {
                        checkoutProvider.set(
                            'billingAddress',
                            $.extend(true, {}, checkoutProvider.get('billingAddress'), billingAddressData)
                        );
                    }
                    checkoutProvider.on('billingAddress', function (billingAddressData) {
                        checkoutData.setBillingAddressFromData(billingAddressData);
                    });
                });
            }
        });
    }
);
