/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'ko',
    'Magento_Checkout/js/view/summary/cart-items',
    'Magento_Checkout/js/view/summary/grand-total',
    'Magento_Checkout/js/model/totals'
], function (ko, Component, grandTotal, totals) {
    'use strict';

    return Component.extend({
        isItemsHidden: window.checkoutConfig.numberProductInCheckout.hideProducts,
        numberOfItems: window.checkoutConfig.numberProductInCheckout.numberOfProducts,
        productClasses: 'product-item',

        /**
         *
         * @returns {boolean}
         */
        isItemsBlockExpanded: function () {
            return !(this.isItemsHidden === true && (this.numberOfItems === "0" || this.numberOfItems === null))
        },

        getTotals: ko.computed(function () {
            return parseFloat(totals.totals()['items_qty']);
        }, this),

        /**
         *
         * @param items
         */
        setItems: function (items) {
            if (this.isCustomiseItems() === true) {
                items = items.slice(0, parseInt(this.numberOfItems, 10));
            }
            this.items(items);
        },

        /**
         *
         * @returns {boolean}
         */
        isCustomiseItems: function () {
            return this.isItemsBlockExpanded() && this.isItemsHidden;
        },

        /**
         *
         * @returns {boolean}
         */
        isViewCartLinkShowed: function () {
            return this.isCustomiseItems() === true && (this.getCartLineItemsCount() > this.numberOfItems);
        },

        getOrderTotal: ko.computed(function () {
            return grandTotal().getValue();
        }, this)
    });
});
