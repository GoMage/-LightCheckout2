define(
    [
        'Magento_Checkout/js/model/quote',
        'mage/storage',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Checkout/js/model/payment/method-converter',
        'Magento_Checkout/js/model/payment-service',
        'Magento_Checkout/js/model/shipping-service',
        'GoMage_LightCheckout/js/model/resource-url-manager',
        'Magento_Customer/js/customer-data',
        '../view/shipping-state',
        'uiRegistry'
    ],
    function (
        quote,
        storage,
        errorProcessor,
        fullScreenLoader,
        methodConverter,
        paymentService,
        shippingService,
        resourceUrlManager,
        customerData,
        shippingState,
        registry
    ) {
        'use strict';

        return function (itemId) {
            var serviceUrl = resourceUrlManager.getUrlForRemoveItem(quote.getQuoteId(), itemId);

            fullScreenLoader.startLoader();

            return storage.delete(
                serviceUrl
            ).done(
                function (response) {
                    if (response.redirect_url) {
                        customerData.invalidate(['cart']);
                        window.location.href = response.redirect_url;
                        return;
                    }
                    quote.setTotals(response.totals);
                    paymentService.setPaymentMethods(methodConverter(response.payment_methods));
                    if (response.shipping_methods && response.quote_type !== 'virtual') {
                        shippingService.setShippingRates(response.shipping_methods);
                    } else if (response.quote_type === 'virtual') {
                        registry.get("checkout.shippingAddress", shippingState.updateShippingBlocks);
                    }
                }
            ).fail(
                function (response) {
                    errorProcessor.process(response);
                }
            ).always(function () {
                fullScreenLoader.stopLoader();
            });
        };
    }
);
