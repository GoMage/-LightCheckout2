
define(
    [
        'ko',
        'Magento_Checkout/js/view/payment',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Checkout/js/model/payment/additional-validators'
    ],
    function (
        ko,
        Component,
        quote,
        stepNavigator,
        additionalValidators
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Magento_Checkout/payment'
            },
            isVisible: ko.observable(true),
            errorValidationMessage: ko.observable(false),

            initialize: function () {
                var self = this;

                this._super();

                stepNavigator.steps.removeAll();

                additionalValidators.registerValidator(this);

                quote.paymentMethod.subscribe(function () {
                    self.errorValidationMessage(false);
                });

                return this;
            },

            validate: function () {
                if (!quote.paymentMethod()) {
                    this.errorValidationMessage('Please specify a payment method.');

                    return false;
                }

                return true;
            }
        });
    }
);
