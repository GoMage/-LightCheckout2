define([], function () {
    'use strict';
    var isLightCheckoutEnable = window.checkoutConfig.isLightCheckoutEnable;
    return {
        getIsLightCheckoutEnable: isLightCheckoutEnable,
    }
});
