define([], function () {
    'use strict';
    var isLightCheckoutEnable = window.checkoutConfig.isLightCheckoutEnable;
    if (isLightCheckoutEnable === undefined) {
        isLightCheckoutEnable = false;
    }
    return {
        getIsLightCheckoutEnable: isLightCheckoutEnable,
    }
});
