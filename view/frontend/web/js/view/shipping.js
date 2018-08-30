define(
    [
        'ko',
        'jquery',
        'Magento_Checkout/js/view/shipping',
        'Magento_Checkout/js/model/quote',
        'uiRegistry',
        'Magento_Checkout/js/checkout-data',
        'Magento_Checkout/js/model/shipping-rates-validator',
        'Magento_Checkout/js/action/set-shipping-information',
        'Magento_Checkout/js/action/select-shipping-address',
        'Magento_Checkout/js/action/set-billing-address',
        'Magento_Ui/js/model/messageList',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/model/address-converter',
        'Magento_Checkout/js/action/create-shipping-address'
    ],
    function (
        ko,
        $,
        Component,
        quote,
        registry,
        checkoutData,
        shippingRatesValidator,
        setShippingInformationAction,
        selectShippingAddress,
        setBillingAddressAction,
        globalMessageList,
        additionalValidators,
        customer,
        addressConverter,
        createShippingAddress
    ) {
        'use strict';

        return Component.extend({
            initialize: function () {
                var self = this;
                var fieldsetName = 'checkout.shippingAddress.shipping-address-fieldset';

                this._super();

                registry.async('checkoutProvider')(function (checkoutProvider) {
                    var shippingAddressData = checkoutData.getShippingAddressFromData();

                    if (shippingAddressData) {
                        checkoutProvider.set(
                            'shippingAddress',
                            $.extend(true, {}, checkoutProvider.get('shippingAddress'), shippingAddressData)
                        );
                    }
                    checkoutProvider.on('shippingAddress', function (shippingAddressData) {
                        checkoutData.setShippingAddressFromData(shippingAddressData);
                    });
                    shippingRatesValidator.initFields(fieldsetName);
                });

                quote.billingAddress.subscribe(function (newAddress) {
                    if (self.isAddressSameAsShipping()) {
                        selectShippingAddress(newAddress);
                    }
                });

                additionalValidators.registerValidator(this);

                return this;
            },

            initObservable: function () {
                this._super()
                    .observe({
                        isAddressSameAsShipping: false
                    });

                quote.shippingMethod.subscribe(function (oldValue) {
                    this.currentMethod = oldValue;
                }, this, 'beforeChange');

                quote.shippingMethod.subscribe(function (newValue) {
                    var isMethodChange = ($.type(this.currentMethod) !== 'object') ? true : this.currentMethod.method_code;
                    if ($.type(newValue) === 'object' && (isMethodChange !== newValue.method_code)) {
                        setShippingInformationAction();
                    }
                }, this);

                return this;
            },

            getShippingMethodsTemplate: function () {
                return 'GoMage_LightCheckout/form/shipping-methods';
            },

            canUseShippingAddress: ko.computed(function () {
                return !quote.isVirtual() && quote.shippingAddress() && quote.shippingAddress().canUseForBilling();
            }),

            /**
             * @inheritDoc
             */
            useShippingAddress: function () {
                if (this.isAddressSameAsShipping()) {
                    selectShippingAddress(quote.billingAddress());

                    if (window.checkoutConfig.reloadOnBillingAddress ||
                        !window.checkoutConfig.displayBillingOnPaymentMethod
                    ) {
                        setBillingAddressAction(globalMessageList);
                    }
                } else {
                    var addressData = this.source.get('shippingAddress');

                    this.isAddressSameAsShipping(false);

                    selectShippingAddress(createShippingAddress(addressData));
                }

                return true;
            },

            validate: function () {
                if (quote.isVirtual()) {
                    return true;
                }

                var shippingMethodValidationResult = true,
                    shippingAddressValidationResult = true,
                    loginFormSelector = 'form[data-role=email-with-possible-login]',
                    emailValidationResult = customer.isLoggedIn();

                if (!quote.shippingMethod()) {
                    this.errorValidationMessage('Please specify a shipping method.');

                    shippingMethodValidationResult = false;
                }

                if (!customer.isLoggedIn()) {
                    $(loginFormSelector).validation();
                    emailValidationResult = Boolean($(loginFormSelector + ' input[name=username]').valid());
                }

                if (!this.isAddressSameAsShipping()) {
                    this.source.set('params.invalid', false);
                    this.source.trigger('shippingAddress.data.validate');

                    if (this.source.get('shippingAddress.custom_attributes')) {
                        this.source.trigger('shippingAddress.custom_attributes.data.validate');
                    }

                    if (this.source.get('params.invalid')) {
                        shippingAddressValidationResult = false;
                    }

                    var addressData = addressConverter.formAddressDataToQuoteAddress(
                        this.source.get('shippingAddress')
                    );
                    selectShippingAddress(addressData);
                }

                if (!emailValidationResult) {
                    $(loginFormSelector + ' input[name=username]').focus();
                }

                return shippingMethodValidationResult && shippingAddressValidationResult && emailValidationResult;
            }
        });
    }
);
