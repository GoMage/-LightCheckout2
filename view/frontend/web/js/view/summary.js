/*global define*/
define(
    [
        'jquery',
        'Magento_Checkout/js/view/summary',
        'sticky',
        'mage/mage',
        'domReady!'
    ],
    function($, Component) {
        'use strict';
        return Component.extend({
            afterRenderSummary: function () {
                $('.glc-right-col').mage('sticky', {
                    container: '#maincontent'
                });
            }
        });
    }
);
