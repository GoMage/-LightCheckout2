define(
    [
        'uiComponent',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/model/quote',
    ],
    function (
        Component,
        customer,
        quote
    ) {
        'use strict';

        return Component.extend({
            isCustomerLoggedIn: customer.isLoggedIn,
            isQuoteVirtual: quote.isVirtual()
        });
    }
);
