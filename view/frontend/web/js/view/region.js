define([
    'underscore',
    'Magento_Ui/js/form/element/region',
    'mageUtils',
    'uiLayout'
], function (_, Component, utils, layout) {
    'use strict';

    var inputNode = {
        parent: '${ $.$data.parentName }',
        component: 'Magento_Ui/js/form/element/abstract',
        template: '${ $.$data.template }',
        placeholder: '${ $.$data.inputPlaceholder }',
        provider: '${ $.$data.provider }',
        name: '${ $.$data.index }_input',
        dataScope: '${ $.$data.customEntry }',
        customScope: '${ $.$data.customScope }',
        sortOrder: {
            after: '${ $.$data.name }'
        },
        displayArea: 'body',
        label: '${ $.$data.label }'
    };

    return Component.extend({
        initInput: function () {
            layout([utils.template(_.extend(
                inputNode,
                {
                    additionalClasses: this.additionalClasses,
                    tooltip: this.tooltip
                }
            ), this)]);

            return this;
        }
    });
});
