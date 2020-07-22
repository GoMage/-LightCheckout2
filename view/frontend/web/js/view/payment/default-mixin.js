define(
    [
        'uiRegistry',
        'underscore'
    ],
    function (uiRegistry, _) {
        'use strict';

        return function (paymentDefault) {
            var isEnable = false;
            if (isEnable) {
                return paymentDefault.extend({
                    initChildren: function () {
                        this.messageContainer = uiRegistry.get('checkout.errors').messageContainer;

                        return this;
                    }
                });
            }
            return paymentDefault;
        }
    });
