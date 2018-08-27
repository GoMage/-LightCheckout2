define(
    [
        'Magento_Checkout/js/view/summary/shipping',
        'Magento_Checkout/js/model/quote'
    ],
    function (Component, quote) {
        return Component.extend({
            isCalculated: function() {
                return this.totals() && null != quote.shippingMethod();
            }
        });
    }
);
