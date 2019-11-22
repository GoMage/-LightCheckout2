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
            optional: 'Optional',
            afterRenderSummary: function () {
                var loaderElement = '#checkout-loader .loader',
                    summaryElement = '.glc-right-col';
                var self = this;
                var checkExist = setInterval(function () {
                    if (!$(loaderElement).is(':visible')) {
                        $(summaryElement).mage('sticky', {
                            container: '#maincontent'
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
                console.log(this.optional);
                $('label.label:contains('+this.optional+')').html(
                    $('label.label:contains('+this.optional+')').html()
                        .replace(
                            '(' + this.optional + ')',
                            '<span class="optional">(' + this.optional + ')</span>'
                        )
                );
            }
        });
    }
);

