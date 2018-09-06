<?php

namespace GoMage\LightCheckout\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class CheckoutConfigurationsProvider
{
    /**#@+
     * Light Checkout configuration General Tab settings.
     */
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_IS_ENABLED = 'light_checkout_configuration/general/is_enabled';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_DEFAULT_SHIPPING_METHOD = 'light_checkout_configuration/general/default_shipping_method';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_DEFAULT_PAYMENT_METHOD = 'light_checkout_configuration/general/default_payment_method';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_PAGE_TITLE = 'light_checkout_configuration/general/page_title';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_PAGE_CONTENT = 'light_checkout_configuration/general/page_content';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_ENABLE_DIFFERENT_SHIPPING_ADDRESS = 'light_checkout_configuration/general/enable_different_shipping_address';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_ALLOW_TO_CHANGE_QTY = 'light_checkout_configuration/general/allow_to_change_qty';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_ALLOW_TO_REMOVE_ITEM_FROM_CHECKOUT = 'light_checkout_configuration/general/allow_to_remove_item_from_checkout';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_DISABLE_CART = 'light_checkout_configuration/general/disable_cart';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_ENABLE_DISCOUNT_CODES = 'light_checkout_configuration/general/enable_discount_codes';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_SHOW_ORDER_SUMMARY_ON_SUCCESS_PAGE = 'light_checkout_configuration/general/show_order_summary_on_success_page';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Registration Tab settings.
     */
    const XML_PATH_LIGHT_CHECKOUT_REGISTRATION_CHECKOUT_MODE = 'light_checkout_configuration/registration/checkout_mode';
    const XML_PATH_LIGHT_CHECKOUT_REGISTRATION_AUTO_REGISTRATION = 'light_checkout_configuration/registration/auto_registration';
    const XML_PATH_LIGHT_CHECKOUT_REGISTRATION_CREATE_AN_ACCOUNT_CHECKBOX = 'light_checkout_configuration/registration/create_an_account_checkbox';
    const XML_PATH_LIGHT_CHECKOUT_REGISTRATION_ALLOW_GUEST_REGISTRATION_ON_SUCCESS_PAGE = 'light_checkout_configuration/registration/allow_guest_registration_on_success_page';
    /**#@-*/

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function isLightCheckoutEnabled()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_IS_ENABLED);
    }

    public function getDefaultShippingMethod()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_DEFAULT_SHIPPING_METHOD);
    }

    public function getDefaultPaymentMethod()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_DEFAULT_PAYMENT_METHOD);
    }

    public function getPageTitle()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_PAGE_TITLE);
    }

    public function getPageContent()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_PAGE_CONTENT);
    }

    public function getEnableDifferentShippingAddress()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_ENABLE_DIFFERENT_SHIPPING_ADDRESS);
    }

    public function getIsAllowedToChangeQty()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_ALLOW_TO_CHANGE_QTY);
    }

    public function getIsAllowedToRemoveItemFromCheckout()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_ALLOW_TO_REMOVE_ITEM_FROM_CHECKOUT);
    }

    public function getIsDisabledCart()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_DISABLE_CART);
    }

    public function getIsEnabledDiscountCodes()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_ENABLE_DISCOUNT_CODES);
    }

    public function getIsShownOrderSummaryOnSuccessPage()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GENERAL_SHOW_ORDER_SUMMARY_ON_SUCCESS_PAGE);
    }

    public function getCheckoutMode()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_REGISTRATION_CHECKOUT_MODE);
    }

    public function getIsAutoRegistration()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_REGISTRATION_AUTO_REGISTRATION);
    }

    public function getCreateAnAccountCheckbox()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_REGISTRATION_CREATE_AN_ACCOUNT_CHECKBOX);
    }

    public function getAllowGuestRegistrationOnSuccessPage()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_REGISTRATION_ALLOW_GUEST_REGISTRATION_ON_SUCCESS_PAGE);
    }
}
