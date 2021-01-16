define([
    'jquery',
    'uiComponent'
], function ($,uiComponent) {
    'use strict';

    return uiComponent.extend({
        defaults: {
            listens: {
                '${ $.name }.fields:elems': 'onUpdateRecordsPosition',
                '${ $.name }.fields:recordData': 'onUpdateRecordData'
            },
            imports: {
                fieldsSize: '${ $.name }_data_source:data.fields',
                fields: '${ $.name }.fields:elems'
            },
            inputValue: '',
            tracks: {
                inputValue: true, fields: true
            }
        },

        getFieldsSize: function () {
            return this.fieldsSize.length;
        },

        onUpdateRecordsPosition: function () {
            this.onUpdateRecordData([]);
        },

        onUpdateRecordData: function (recordDataArray) {
            if (this.fields.length === this.getFieldsSize()) {
                var fields = [];
                $.each(this.fields, function (index, elem) {
                    var label, isWide, is_enabled, is_required;
                    $.each(recordDataArray, function (index, recordData) {
                        if (recordData.attributeCode === elem.data().attributeCode) {
                            label = recordData.label;
                            isWide = recordData.width;
                            is_enabled = recordData.is_enabled.value;
                            is_required = recordData.is_required.value;
                        }
                    });
                    fields.push({
                        attributeCode: elem.data().attributeCode,
                        label: typeof label !== 'undefined' ? label : elem.data().label,
                        isWide: typeof isWide !== 'undefined' ? isWide : elem.data().width,
                        is_enabled: typeof is_enabled !== 'undefined' ? is_enabled : elem.data().is_enabled.value,
                        is_required: typeof is_required !== 'undefined' ? is_required : elem.data().is_required.value
                    });
                });

                this.inputValue = JSON.stringify(fields);
            }
        }
    });
});
