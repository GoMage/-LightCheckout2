define(
    [
        'uiComponent',
        'Magento_Customer/js/model/customer',
        './shipping-state',
        'uiRegistry',
        'jquery'
    ],
    function (
        Component,
        customer,
        shippingState,
        uiRegistry,
        $
    ) {
        'use strict';

        return Component.extend({
            isCustomerLoggedIn: customer.isLoggedIn,
            isQuoteVirtual: !shippingState.canUseShippingAddress,

            /**
             * @returns {string}
             */
            getColumnClass: function () {
                if (uiRegistry.get('checkout').configuration.is3ColumnType){
                    $('body').addClass('lightcheckout-3-column');
                    return 'three-column';
                }
                return '';
            }
        });
    }
);
