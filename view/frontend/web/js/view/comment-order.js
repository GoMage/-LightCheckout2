define(
    [
        'jquery',
        'ko',
        'uiComponent'
    ],
    function (
        $,
        ko,
        Component
    ) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'GoMage_LightCheckout/form/comment-order'
            },
            initialize: function () {
                this._super();
                return this;
            }
        });
    }
);
