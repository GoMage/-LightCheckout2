define(['ko', 'Magento_Checkout/js/model/quote'], function (ko, quote) {
    'use strict';

    return ko.track({
        isPlaceOrderActionAllowed: quote.billingAddress() != null
    });
});
