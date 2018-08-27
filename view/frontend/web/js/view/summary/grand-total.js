define(
    [
        'Magento_Checkout/js/view/summary/grand-total'
    ],
    function (Component) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Magento_Checkout/summary/grand-total'
            },
            isDisplayed: function() {
                return true;
            }
        });
    }
);
