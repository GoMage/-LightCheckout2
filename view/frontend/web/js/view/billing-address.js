define(
    [
        'jquery',
        'Magento_Checkout/js/view/billing-address',
        'uiRegistry',
        'Magento_Checkout/js/checkout-data',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/address-converter',
        'Magento_Checkout/js/action/select-billing-address',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Magento_Checkout/js/model/shipping-rates-validation-rules',
        'Magento_Checkout/js/model/postcode-validator',
        'mage/translate',
        'underscore',
        'Magento_Customer/js/model/customer',
        'GoMage_LightCheckout/js/model/address/auto-complete-register',
        'rjsResolver'
    ],
    function (
        $,
        Component,
        uiRegistry,
        checkoutData,
        quote,
        addressConverter,
        selectBillingAddress,
        additionalValidators,
        shippingRatesValidationRules,
        postcodeValidator,
        $t,
        _,
        customer,
        autoCompleteRegister,
        rjsResolver
    ) {
        'use strict';

        var observedElements = [],
            postcodeElement = null,
            postcodeElementName = 'postcode';

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

                rjsResolver(this.registerAutoComplete.bind(this));

                return this;
            },

            registerAutoComplete: function () {
                autoCompleteRegister.register('billing');
            },

            initObservable: function () {
                this._super()
                    .observe({
                        isAddressFormVisible: true,
                        isAddressNew: false
                    });

                // check if not only new address present
                if (this.addressOptions.length > 1) {
                    for (var i = 0; i < this.addressOptions.length; i++) {
                        if (this.addressOptions[i].isDefaultBilling()) {
                            this.selectedAddress(this.addressOptions[i]);
                            break;
                        }
                    }
                }

                return this;
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

                if (customer.isLoggedIn() && !this.customerHasAddresses) {
                    this.saveInAddressBook(1);
                }

                addressData['save_in_address_book'] = this.saveInAddressBook() ? 1 : 0;
                addressData.saveInAddressBook = this.saveInAddressBook() ? 1 : 0;

                selectBillingAddress(addressData);

                return !this.source.get('params.invalid');
            },

            /**
             * Perform postponed binding for fieldset elements
             */
            initFields: function () {
                var formPath = 'checkout.billingAddress.billing-address-fieldset',
                    self = this,
                    elements = shippingRatesValidationRules.getObservableFields();

                if ($.inArray(postcodeElementName, elements) === -1) {
                    // Add postcode field to observables if not exist for zip code validation support
                    elements.push(postcodeElementName);
                }

                $.each(elements, function (index, field) {
                    uiRegistry.async(formPath + '.' + field)(self.doElementBinding.bind(self));
                });
            },

            /**
             * Bind shipping rates request to form element
             *
             * @param {Object} element
             * @param {Boolean} force
             * @param {Number} delay
             */
            doElementBinding: function (element, force, delay) {
                var observableFields = shippingRatesValidationRules.getObservableFields();

                if (element && (observableFields.indexOf(element.index) !== -1 || force)) {
                    if (element.index !== postcodeElementName) {
                        this.bindHandler(element, delay);
                    }
                }

                if (element.index === postcodeElementName) {
                    this.bindHandler(element, delay);
                    postcodeElement = element;
                }
            },

            /**
             * @param {Object} element
             * @param {Number} delay
             */
            bindHandler: function (element, delay) {
                var self = this;

                delay = typeof delay === 'undefined' ? self.validateDelay : delay;

                if (element.component.indexOf('/group') !== -1) {
                    $.each(element.elems(), function (index, elem) {
                        self.bindHandler(elem);
                    });
                } else {
                    element.on('value', function () {
                        clearTimeout(self.validateAddressTimeout);
                        self.validateAddressTimeout = setTimeout(function () {
                            if (self.postcodeValidation()) {
                                self.validateFields();
                            }
                        }, delay);

                    });
                    observedElements.push(element);
                }
            },

            /**
             * @return {*}
             */
            postcodeValidation: function () {
                var countryId = $('select[name="country_id"]').val(),
                    validationResult,
                    warnMessage;

                if (postcodeElement == null || postcodeElement.value() == null) {
                    return true;
                }

                postcodeElement.warn(null);
                validationResult = postcodeValidator.validate(postcodeElement.value(), countryId);

                if (!validationResult) {
                    warnMessage = $t('Provided Zip/Postal Code seems to be invalid.');

                    if (postcodeValidator.validatedPostCodeExample.length) {
                        warnMessage += $t(' Example: ') + postcodeValidator.validatedPostCodeExample.join('; ') + '. ';
                    }
                    warnMessage += $t('If you believe it is the right one you can ignore this notice.');
                    postcodeElement.warn(warnMessage);
                }

                return validationResult;
            },

            /**
             * Convert form data to quote address and validate fields for shipping rates
             */
            validateFields: function () {
                var addressFlat,
                    address;

                    addressFlat = uiRegistry.get('checkoutProvider').billingAddress;
                    address = addressConverter.formAddressDataToQuoteAddress(addressFlat);
                    selectBillingAddress(address);
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

            /**
             * @inheritDoc
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

                this.source.set('billingAddress', address);
            }
        });
    }
);
