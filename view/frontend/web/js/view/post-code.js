/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/post-code',
    'GoMage_LightCheckout/js/action/get-address-by-post-code'
], function (_, registry, PostCode, getAddressByPostCodeAction) {
    'use strict';

    return PostCode.extend({
        onFocusOut: function (element) {
            getAddressByPostCodeAction(element.value(), this.parentName)
        }
    });
});
