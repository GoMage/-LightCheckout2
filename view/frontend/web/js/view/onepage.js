define(
    [
        'uiComponent',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/model/quote',
        'uiRegistry',
    ],
    function (
        Component,
        customer,
        quote,
        uiRegistry

    ) {
        'use strict';

        return Component.extend({
            isCustomerLoggedIn: customer.isLoggedIn,
            isQuoteVirtual: quote.isVirtual(),

            /**
             * @returns {string}
             */
            getColumnClass: function () {
                if(uiRegistry.get('checkout').configuration.is3ColumnType){
                    return '3-column';
                }
                return '';
            }
        });
    }
);
