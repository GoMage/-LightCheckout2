define([
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/model/customer',
    'mage/utils/wrapper',
    'GoMage_LightCheckout/js/is-light-checkout-enable'
], function (quote, customer, wrapper, isModuleEnable) {
    'use strict';

    return function (setPaymentInformationExtendedFunction) {
        var isEnable = isModuleEnable.getIsLightCheckoutEnable;
        if (isEnable) {
            return wrapper.wrap(setPaymentInformationExtendedFunction, function (
                originalSetPaymentInformationExtendedFunction, messageContainer, paymentData, skipBilling
            ) {
                if (!customer.isLoggedIn()) {
                    if (quote.guestEmail) {
                        return originalSetPaymentInformationExtendedFunction(messageContainer, paymentData, skipBilling);
                    }
                    return;
                } else {
                    return originalSetPaymentInformationExtendedFunction(messageContainer, paymentData, skipBilling);
                }
            });
        }
        return setPaymentInformationExtendedFunction;
    };
});
