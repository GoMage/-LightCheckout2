define([
    'Magento_Ui/js/form/element/single-checkbox',
    'mage/translate'
], function (coreSingleCheckbox, $t) {
    'use strict';

    return coreSingleCheckbox.extend({
        defaults: {
            tracks: {
                attributeCode: true
            }
        },

        getTooltip: function () {
            if (this.attributeCode === 'postcode') {
                return $t('To configure ZIP/Postal requirement, go to Stores > Configuration > General ' +
                    '> General > State Options');
            }
            if (this.attributeCode === 'region_id') {
                return $t('To configure State/Province requirement, go to Stores > Configuration > General ' +
                    '> General > Country Options');
            }
            return '';
        }
    });
});
