define(
    [
    'mage/utils/wrapper'
    ],
    function (wrapper) {
    'use strict';

    var mixin = {

        getObservableFields: function (fn) {
            var observableFields = fn();

            observableFields.push('telephone');
            observableFields.push('street');

            return observableFields;
        }
    }

    return function (target) {
        return wrapper.extend(target, mixin);
    };
});
