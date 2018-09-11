define(
    [
        'jquery',
        'Magento_Checkout/js/view/billing-address',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Magento_Customer/js/model/customer'
    ],
    function (
        $,
        Component,
        quote,
        additionalValidators,
        customer
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'GoMage_LightCheckout/form/billing-address/create-account'
            },
            passwordMinLength: window.checkoutConfig.passwordSettings.minimumPasswordLength,
            passwordMinCharacterSets: window.checkoutConfig.passwordSettings.requiredCharacterClassesNumber,

            initialize: function () {
                this._super();

                additionalValidators.registerValidator(this);
            },

            initObservable: function () {
                var shouldCreateAccountBeVisible = false,
                    shouldCheckboxBeVisible = false,
                    shouldIsCreateAnAccountCheckboxChecked = parseInt(window.checkoutConfig.registration.isCreateAnAccountCheckboxChecked),
                    autoRegistration = window.checkoutConfig.registration.autoRegistration,
                    checkoutMode = window.checkoutConfig.registration.checkoutMode;

                if (autoRegistration === '0') {
                    if (checkoutMode == 0 && !customer.isLoggedIn()) {
                        shouldCreateAccountBeVisible = true;
                        shouldCheckboxBeVisible = true;
                    }

                    if (checkoutMode == 1 && !customer.isLoggedIn()) {
                        shouldCreateAccountBeVisible = true;
                        shouldCheckboxBeVisible = false;
                        shouldIsCreateAnAccountCheckboxChecked = true;
                    }

                    if (checkoutMode == 2 && !customer.isLoggedIn()) {
                        shouldCreateAccountBeVisible = false;
                    }
                }

                if (customer.isLoggedIn() || autoRegistration === '1') {
                    shouldCreateAccountBeVisible = false;
                    shouldCheckboxBeVisible = false;
                    shouldIsCreateAnAccountCheckboxChecked = false;
                }

                this._super()
                    .observe({
                        isCreateAccountVisible: shouldCreateAccountBeVisible,
                        isCheckboxVisible: shouldCheckboxBeVisible,
                        isCreateAnAccountCheckboxChecked: shouldIsCreateAnAccountCheckboxChecked
                    });

                return this;
            },

            onCreateAccountClick: function (value) {
                this.isPasswordVisible(value);
            },

            validate: function () {
                var result = null;

                if (this.isCreateAnAccountCheckboxChecked()) {
                    var passwordSelector = $('#account-password');
                    passwordSelector.parents('form').validation();

                    var password = !!passwordSelector.valid();
                    var confirm = !!$('#account-password-confirmation').valid();

                    result = password && confirm;
                } else {
                    result = true;
                }

                return result;
            }
        })
    });
