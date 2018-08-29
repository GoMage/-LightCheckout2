define(
    [
        'jquery',
        'Magento_Checkout/js/view/billing-address',
        'uiRegistry',
        'Magento_Checkout/js/checkout-data',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/address-converter',
        'Magento_Checkout/js/action/select-billing-address',
        'Magento_Checkout/js/model/payment/additional-validators'
    ],
    function (
        $,
        Component,
        uiRegistry,
        checkoutData,
        quote,
        addressConverter,
        selectBillingAddress,
        additionalValidators
    ) {
        'use strict';

        var observedElements = [];

        return Component.extend({
            initialize: function () {
                this._super();

                uiRegistry.async('checkoutProvider')(function (checkoutProvider) {
                    var billingAddressData = checkoutData.getBillingAddressFromData();

                    if (billingAddressData) {
                        checkoutProvider.set(
                            'billingAddress',
                            $.extend(true, {}, checkoutProvider.get('billingAddress'), billingAddressData)
                        );
                    }
                    checkoutProvider.on('billingAddress', function (billingAddressData) {
                        checkoutData.setBillingAddressFromData(billingAddressData);
                    });
                });

                this.initFields();

                additionalValidators.registerValidator(this);
            },


            initFields: function () {
                var self = this,
                    addressFields = window.checkoutConfig.lightCheckoutConfig.addressFields,
                    fieldsetName = 'checkout.billingAddress.billing-address-fieldset';

                $.each(addressFields, function (index, field) {
                    uiRegistry.async(fieldsetName + '.' + field)(self.bindHandler.bind(self));
                });

                return this;
            },

            bindHandler: function (element) {
                var self = this;
                if (element.component.indexOf('/group') !== -1) {
                    $.each(element.elems(), function (index, elem) {
                        self.bindHandler(elem);
                    });
                } else {
                    element.on('value', this.saveBillingAddress.bind(this));
                    observedElements.push(element);
                }
            },

            saveBillingAddress: function () {
                var addressFlat = addressConverter.formDataProviderToFlatData(
                    this.collectObservedData(),
                    'billingAddress'
                );
                selectBillingAddress(addressConverter.formAddressDataToQuoteAddress(addressFlat));
            },

            /**
             * Collect observed fields data to object
             *
             * @returns {*}
             */
            collectObservedData: function () {
                var observedValues = {};

                $.each(observedElements, function (index, field) {
                    observedValues[field.dataScope] = field.value();
                });

                return observedValues;
            },

            validate: function () {
                this.source.set('params.invalid', false);
                this.source.trigger('billingAddress.data.validate');

                if (this.source.get('billingAddress.custom_attributes')) {
                    this.source.trigger('billingAddress.custom_attributes.data.validate');
                }

                var addressData = addressConverter.formAddressDataToQuoteAddress(
                    this.source.get('billingAddress')
                );
                selectBillingAddress(addressData);

                return !this.source.get('params.invalid');
            }
        });
    }
);
