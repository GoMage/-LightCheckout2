define(
    [
        'uiComponent',
        'Magento_Customer/js/model/customer',
        './shipping-state',
        'jquery'
    ],
    function (
        Component,
        customer,
        shippingState,
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
                if (this.configuration.is3ColumnType){
                    $('body').addClass('lightcheckout-3-column');
                    return 'three-column';
                }
                return '';
            }
        });
    }
);
