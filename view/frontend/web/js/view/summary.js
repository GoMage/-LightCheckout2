/*global define*/
define(
    [
        'jquery',
        'Magento_Checkout/js/view/summary',
        'sticky',
        'mage/mage',
        'domReady!'
    ],
    function ($, Component) {
        'use strict';
        return Component.extend({
            defaults: {
                stickySelector: '#maincontent'
            },
            optional: 'Optional',

            afterRenderSummary: function () {
                var loaderElement = '#checkout-loader .loader',
                    summaryElement = '.glc-right-col';
                var self = this;
                var checkExist = setInterval(function () {
                    if (!$(loaderElement).is(':visible')) {
                        $(summaryElement).mage('sticky', {
                            container: self.stickySelector
                        });
                        self.specialChangesFields();
                        clearInterval(checkExist);
                    }
                }, 500);
            },

            /**
             * add custom thing to design
             */
            specialChangesFields: function () {
                var self = this;
                $.each($('label.label:contains('+this.optional+'), legend.label:contains('+this.optional+')'),
                    function( index, element ) {
                        element = $(element);
                        element.html(
                            element.html()
                                .replace(
                                    '(' + self.optional + ')',
                                    '<span class="optional">(' + self.optional + ')</span>'
                                )
                        );
                });
            }
        });
    }
);

