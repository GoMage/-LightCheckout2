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
    /**#@-*/

    /**#@+
     * Light Checkout configuration Devices.
     */
    const XML_PATH_LIGHT_CHECKOUT_DEVICES_DESKTOP = 'light_checkout_configuration/devices/desktop';
    const XML_PATH_LIGHT_CHECKOUT_DEVICES_TABLET = 'light_checkout_configuration/devices/tablet';
    const XML_PATH_LIGHT_CHECKOUT_DEVICES_SMARTPHONE = 'light_checkout_configuration/devices/smartphone';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Delivery Date.
     */
    const XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_ENABLE = 'light_checkout_configuration/delivery_date/enable_delivery_date';
    const XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_SHOW_TIME = 'light_checkout_configuration/delivery_date/show_time';
    const XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_DELIVERY_DAYS = 'light_checkout_configuration/delivery_date/delivery_days';
    const XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_NON_WORKING_DAYS = 'light_checkout_configuration/delivery_date/non_working_days';
    const XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_INTERVAL = 'light_checkout_configuration/delivery_date/set_interval_for_delivery';
    const XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_FORMAT = 'light_checkout_configuration/delivery_date/date_format';
    const XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_USE_FOR = 'light_checkout_configuration/delivery_date/use_delivery_date_for';
    const XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_DISPLAY_TEXT = 'light_checkout_configuration/delivery_date/display_delivery_date_text';
    const XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_TEXT = 'light_checkout_configuration/delivery_date/delivery_date_text';
    /**#@-*/

    /**#@+
     * Light Checkout configuration GeoIP.
     */
    const XML_PATH_LIGHT_CHECKOUT_GEOIP_FOR_COUNTRY = 'light_checkout_configuration/geoip/enable_for_country';
    const XML_PATH_LIGHT_CHECKOUT_GEOIP_FOR_STATE = 'light_checkout_configuration/geoip/enable_for_state';
    const XML_PATH_LIGHT_CHECKOUT_GEOIP_FOR_ZIP = 'light_checkout_configuration/geoip/enable_for_zip';
    const XML_PATH_LIGHT_CHECKOUT_GEOIP_FOR_CITY = 'light_checkout_configuration/geoip/enable_for_city';

    /**#@-*/

    /**#@+
     * Light Checkout configuration EU Vat/Tax Settings.
     */
    const XML_PATH_LIGHT_CHECKOUT_VAT_TAX_ENABLE = 'light_checkout_configuration/vat/enable';
    const XML_PATH_LIGHT_CHECKOUT_VAT_TAX_VERIFICATION_SYSTEM = 'light_checkout_configuration/vat/verification_system';
    const XML_PATH_LIGHT_CHECKOUT_VAT_TAX_BASE_EU_COUNTRY = 'light_checkout_configuration/vat/base_eu_country';
    const XML_PATH_LIGHT_CHECKOUT_VAT_TAX_B2C_B2B_BASE_EU = 'light_checkout_configuration/vat/b2c_b2b_base_eu';
    const XML_PATH_LIGHT_CHECKOUT_VAT_TAX_B2C_B2B_NOT_BASE_EU = 'light_checkout_configuration/vat/b2c_b2b_not_base_eu';
    const XML_PATH_LIGHT_CHECKOUT_VAT_TAX_RULE = 'light_checkout_configuration/vat/vat_tax_rule';
    const XML_PATH_LIGHT_CHECKOUT_VAT_TAX_SHOW_BUY_WITHOUT_VAT_CHECKBOX = 'light_checkout_configuration/vat/show_buy_without_vat_checkbox';
    const XML_PATH_LIGHT_CHECKOUT_VAT_TAX_TEXT_UNDER_VAT_TAX_FIELD = 'light_checkout_configuration/vat/text_under_vat_tax_field';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Terms and Conditions.
     */
    const XML_PATH_LIGHT_CHECKOUT_TERMS_AND_CONDITIONS_ENABLE = 'light_checkout_configuration/terms_and_conditions/enable';
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

    public function isShowOnDesktopAndLaptop()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_DEVICES_DESKTOP);
    }

    public function getShowOnTabletOperationSystems()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_DEVICES_TABLET);
    }

    public function getShowOnSmartphoneOperationSystems()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_DEVICES_TABLET);
    }

    public function getIsEnabledDeliveryDate()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_ENABLE);
    }

    public function getIsShowTime()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_SHOW_TIME);
    }

    public function getDeliveryDays()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_DELIVERY_DAYS);
    }

    public function getNonWorkingDays()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_NON_WORKING_DAYS);
    }

    public function getIntervalForDelivery()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_INTERVAL);
    }

    public function getDateFormat()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_FORMAT);
    }

    public function getUseDeliveryDateFor()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_USE_FOR);
    }

    public function getIsDisplayDeliveryDateText()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_DISPLAY_TEXT);
    }

    public function getDeliveryDateText()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_TEXT);
    }

    public function getIsEnabledGeoIpForCountry()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GEOIP_FOR_COUNTRY);
    }

    public function getIsEnabledGeoIpForState()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GEOIP_FOR_STATE);
    }

    public function getIsEnabledGeoIpForZip()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GEOIP_FOR_ZIP);
    }

    public function getIsEnabledGeoIpForCity()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GEOIP_FOR_CITY);
    }

    public function getIsEnabledTermsAndConditions()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_TERMS_AND_CONDITIONS_ENABLE);
    }

    public function getIsEnabledVatTax()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_VAT_TAX_ENABLE);
    }

    public function getVatTaxVerificationSystem()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_VAT_TAX_VERIFICATION_SYSTEM);
    }

    public function getVatTaxBaseEuCountry()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_VAT_TAX_BASE_EU_COUNTRY);
    }

    public function getVatTaxB2Cb2BBaseEu()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_VAT_TAX_B2C_B2B_BASE_EU);
    }

    public function getVatTaxB2Cb2BNotBaseEu()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_VAT_TAX_B2C_B2B_NOT_BASE_EU);
    }

    public function getVatTaxRule()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_VAT_TAX_RULE);
    }

    public function getVatTaxShowCheckbox()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_VAT_TAX_SHOW_BUY_WITHOUT_VAT_CHECKBOX);
    }

    public function getVatTaxTextUnderTaxVatField()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_VAT_TAX_TEXT_UNDER_VAT_TAX_FIELD);
    }
}
