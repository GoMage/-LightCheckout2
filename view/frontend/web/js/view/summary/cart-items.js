/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'ko',
    'Magento_Checkout/js/view/summary/cart-items',
    'Magento_Checkout/js/view/summary/grand-total'
], function (ko, Component, grandTotal) {
    'use strict';

    return Component.extend({
        hideProducts: window.checkoutConfig.numberProductInCheckout.hideProducts,
        numberOfProducts: window.checkoutConfig.numberProductInCheckout.numberOfProducts,
        productClasses: 'product-item',

        /**
         *
         * @inheritDoc
         */
        isItemsBlockExpanded: function () {
            return this.hideProducts === false
                || (this.hideProducts === true && this.getCartLineItemsCount() > this.numberOfProducts);
        },

        /**
         *
         * @inheritDoc
         */
        setItems: function (items) {
            this.items(items);
        },

        getOrderTotal: ko.computed(function() {
            return grandTotal().getValue();
        }, this)
    });
});
