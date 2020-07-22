define([], function () {
    'use strict';
    var isLightCheckoutEnable = window.checkoutConfig.isLightCheckoutEnable.enabled;
    return {
        getIsLightCheckoutEnable: isLightCheckoutEnable,
    }
});
