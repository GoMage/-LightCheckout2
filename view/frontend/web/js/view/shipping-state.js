define(['ko'], function (ko) {
    'use strict';

    const viewModel = ko.track({
        canUseShippingAddress: true, // if true - quote is not virtual
    });

    /**
     * Hiding shipping methods block and shipping address form
     * @param component
     */
    viewModel.updateShippingBlocks = function (shippingMethods) {
        shippingMethods.visible(false); // hide shipping methods
        this.canUseShippingAddress = false; // hide shipping address form
    }.bind(viewModel);

    return viewModel;
});
