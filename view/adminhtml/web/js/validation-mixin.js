define(['jquery'], function ($) {
    'use strict';

    return function () {
        $.validator.addMethod(
            'validate-lightcheckout-url',
            function (v) {
                return $.mage.isEmptyNoTrim(v) || /^[A-Za-z0-9_-]+$/.test(v);
            },
            $.mage.__('Please use only letters (a-z or A-Z), numbers (0-9), hyphen (-) or underscore (_) in this field.')
        )
    }
});
