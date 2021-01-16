define(['jquery'], function ($) {
    'use strict';

    return function () {
        $.validator.addMethod(
            'validate-lightcheckout-url',
            function (v) {
                return $.mage.isEmptyNoTrim(v) || /^[A-Za-z0-9_-]+$/.test(v);
            },
            $.mage.__('Please use only letters (a-z or A-Z), numbers (0-9), hyphen (-) or underscore (_) in this field.')
        );

        $.validator.addMethod(
            'validate-lightcheckout-fields-manager',
            function (v) {
                var error = false;
                if (!v.trim(' ').length || !JSON.parse(v).length) {
                    return false;
                }
                $.each(JSON.parse(v), function (index, field) {
                   if (field.label.trim(' ') === '') {
                       error = true;
                       return false;
                   }
                });
                return !error;
            },
            $.mage.__('Please check "Label" column for Checkout Manager Fields rows. They could not be empty. ' +
                'Also the Checkout Fields Manager Grid could not be empty')
        );
    }
});
