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
        'Magento_Checkout/js/action/create-shipping-address',
        'Magento_Customer/js/model/address-list',
        'mage/translate',
        'underscore',
        'GoMage_LightCheckout/js/action/update-sections',
        'GoMage_LightCheckout/js/model/address/auto-complete-register',
        'rjsResolver'
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
        createShippingAddress,
        addressList,
        $t,
        _,
        updateSectionAction,
        autoCompleteRegister,
        rjsResolver
    ) {
        'use strict';

        var newAddressOption = {
            /**
             * Get new address label
             * @returns {String}
             */
            getAddressInline: function () {
                return $t('New Address');
            },
            customerAddressId: null
        },
            addressOptions = addressList().filter(function (address) {
            return address.getType() == 'customer-address';
        });

        addressOptions.push(newAddressOption);

        return Component.extend({
            addressOptions: addressOptions,

            /**
             * @inheritDoc
             */
            initialize: function () {
                var self = this;
                var fieldsetName = 'checkout.shippingAddress.shipping-address-fieldset';

                if (!checkoutData.getSelectedShippingRate() && window.checkoutConfig.general.defaultShippingMethod) {
                    checkoutData.setSelectedShippingRate(window.checkoutConfig.general.defaultShippingMethod);
                }

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

                rjsResolver(this.registerAutoComplete.bind(this));

                return this;
            },

            registerAutoComplete: function () {
                autoCompleteRegister.register('shipping');
            },

            /**
             * @inheritDoc
             */
            initObservable: function () {
                this._super()
                    .observe({
                        isAddressSameAsShipping: false,
                        selectedAddress: null,
                        isAddressNew: false
                    });

                // check if not only new address present
                if (this.addressOptions.length > 1) {
                    for (var i = 0; i < this.addressOptions.length; i++) {
                        if (this.addressOptions[i].isDefaultShipping()) {
                            this.selectedAddress(this.addressOptions[i]);
                            break;
                        }
                    }
                }

                var enableDifferentShippingAddress = parseInt(window.checkoutConfig.general.enableDifferentShippingAddress);

                if (enableDifferentShippingAddress === 0 || enableDifferentShippingAddress === 1) {
                    this.isAddressSameAsShipping(true);
                } else if (enableDifferentShippingAddress === 2) {
                    this.isAddressSameAsShipping(false);
                }

                quote.shippingMethod.subscribe(function (oldValue) {
                    this.currentMethod = oldValue;
                }, this, 'beforeChange');

                quote.shippingMethod.subscribe(function (newValue) {
                    var isMethodChange = ($.type(this.currentMethod) !== 'object') ? true : this.currentMethod.method_code;
                    if ($.type(newValue) === 'object' && (isMethodChange !== newValue.method_code)) {
                        setShippingInformationAction();
                    } else {
                        updateSectionAction();
                    }
                }, this);

                return this;
            },

            /**
             * @returns {string}
             */
            getShippingMethodsTemplate: function () {
                return 'GoMage_LightCheckout/form/shipping-methods';
            },

            /**
             * @inheritDoc
             */
            canUseShippingAddress: ko.computed(function () {
                var enableDifferentShippingAddress = parseInt(window.checkoutConfig.general.enableDifferentShippingAddress);

                return !quote.isVirtual() && quote.shippingAddress() && quote.shippingAddress().canUseForBilling()
                    && enableDifferentShippingAddress;
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

            /**
             * @param {Object} address
             * @return {*}
             */
            addressOptionsText: function (address) {
                return address.getAddressInline();
            },

            /**
             * @returns {boolean}
             */
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

                    if (customer.isLoggedIn() && this.addressOptions.length === 1) {
                        this.saveInAddressBook = 1;
                    }

                    addressData['save_in_address_book'] = this.saveInAddressBook ? 1 : 0;

                    selectShippingAddress(addressData);
                }

                if (!emailValidationResult) {
                    $(loginFormSelector + ' input[name=username]').focus();
                }

                return shippingMethodValidationResult && shippingAddressValidationResult && emailValidationResult;
            },

            /**
             * @param {Object} address
             */
            onAddressChange: function (address) {
                var streetObj = {};

                if (address.customerAddressId !== null) {
                    this.isAddressNew(false);
                    address.country_id = address.countryId;
                    address.region_id = address.regionId;

                    if (_.isArray(address.street)) {
                        //convert array to object to display street values on frontend.
                        for (var i = 0; i < address.street.length; i++) {
                            streetObj[i] = address.street[i];
                        }

                        address.street = streetObj;
                    }
                } else {
                    this.isAddressNew(true);
                }

                this.source.set('shippingAddress', address);
            }
        });
    }
);
