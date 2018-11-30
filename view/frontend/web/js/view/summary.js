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
            afterRenderSummary: function () {
                var loaderElement = '#checkout-loader .loader',
                    summaryElement = '.glc-right-col';

                var checkExist = setInterval(function() {
                    if (!$(loaderElement).is(':visible')) {
                        $(summaryElement).mage('sticky', {
                            container: '#maincontent'
                        });
                        clearInterval(checkExist);
                    }
                }, 500);
            }
        });
    }
);
