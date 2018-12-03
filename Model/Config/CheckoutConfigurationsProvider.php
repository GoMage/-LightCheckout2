<?php

namespace GoMage\LightCheckout\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class CheckoutConfigurationsProvider
{
    // @codingStandardsIgnoreStart
    /**#@+
     * Light Checkout configuration General Tab settings.
     */
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_IS_ENABLED = 'gomage_light_checkout_configuration/general/is_enabled';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_DEFAULT_SHIPPING_METHOD = 'gomage_light_checkout_configuration/general/default_shipping_method';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_DEFAULT_PAYMENT_METHOD = 'gomage_light_checkout_configuration/general/default_payment_method';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_PAGE_TITLE = 'gomage_light_checkout_configuration/general/page_title';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_PAGE_CONTENT = 'gomage_light_checkout_configuration/general/page_content';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_ENABLE_DIFFERENT_SHIPPING_ADDRESS = 'gomage_light_checkout_configuration/general/enable_different_shipping_address';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_ALLOW_TO_CHANGE_QTY = 'gomage_light_checkout_configuration/general/allow_to_change_qty';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_ALLOW_TO_REMOVE_ITEM_FROM_CHECKOUT = 'gomage_light_checkout_configuration/general/allow_to_remove_item_from_checkout';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_DISABLE_CART = 'gomage_light_checkout_configuration/general/disable_cart';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_ENABLE_DISCOUNT_CODES = 'gomage_light_checkout_configuration/general/enable_discount_codes';
    const XML_PATH_LIGHT_CHECKOUT_GENERAL_SHOW_ORDER_SUMMARY_ON_SUCCESS_PAGE = 'gomage_light_checkout_configuration/general/show_order_summary_on_success_page';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Registration Tab settings.
     */
    const XML_PATH_LIGHT_CHECKOUT_REGISTRATION_CHECKOUT_MODE = 'gomage_light_checkout_configuration/registration/checkout_mode';
    const XML_PATH_LIGHT_CHECKOUT_REGISTRATION_AUTO_REGISTRATION = 'gomage_light_checkout_configuration/registration/auto_registration';
    const XML_PATH_LIGHT_CHECKOUT_REGISTRATION_CREATE_AN_ACCOUNT_CHECKBOX = 'gomage_light_checkout_configuration/registration/create_an_account_checkbox';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Devices.
     */
    const XML_PATH_LIGHT_CHECKOUT_DEVICES_DESKTOP = 'gomage_light_checkout_configuration/devices/desktop';
    const XML_PATH_LIGHT_CHECKOUT_DEVICES_TABLET = 'gomage_light_checkout_configuration/devices/tablet';
    const XML_PATH_LIGHT_CHECKOUT_DEVICES_SMARTPHONE = 'gomage_light_checkout_configuration/devices/smartphone';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Delivery Date.
     */
    const XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_ENABLE = 'gomage_light_checkout_configuration/delivery_date/enable_delivery_date';
    const XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_SHOW_TIME = 'gomage_light_checkout_configuration/delivery_date/show_time';
    const XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_DELIVERY_DAYS = 'gomage_light_checkout_configuration/delivery_date/delivery_days';
    const XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_NON_WORKING_DAYS = 'gomage_light_checkout_configuration/delivery_date/non_working_days';
    const XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_INTERVAL = 'gomage_light_checkout_configuration/delivery_date/set_interval_for_delivery';
    const XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_FORMAT = 'gomage_light_checkout_configuration/delivery_date/date_format';
    const XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_USE_FOR = 'gomage_light_checkout_configuration/delivery_date/use_delivery_date_for';
    const XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_DISPLAY_TEXT = 'gomage_light_checkout_configuration/delivery_date/display_delivery_date_text';
    const XML_PATH_LIGHT_CHECKOUT_DELIVERY_DATE_TEXT = 'gomage_light_checkout_configuration/delivery_date/delivery_date_text';
    /**#@-*/

    /**#@+
     * Light Checkout configuration GeoIP.
     */
    const XML_PATH_LIGHT_CHECKOUT_GEOIP_ENABLE = 'gomage_light_checkout_configuration/geoip/enable';
    const XML_PATH_LIGHT_CHECKOUT_GEOIP_FOR_COUNTRY = 'gomage_light_checkout_configuration/geoip/enable_for_country';
    const XML_PATH_LIGHT_CHECKOUT_GEOIP_FOR_STATE = 'gomage_light_checkout_configuration/geoip/enable_for_state';
    const XML_PATH_LIGHT_CHECKOUT_GEOIP_FOR_ZIP = 'gomage_light_checkout_configuration/geoip/enable_for_zip';
    const XML_PATH_LIGHT_CHECKOUT_GEOIP_FOR_CITY = 'gomage_light_checkout_configuration/geoip/enable_for_city';

    /**#@-*/

    /**#@+
     * Light Checkout configuration EU Vat/Tax Settings.
     */
    const XML_PATH_LIGHT_CHECKOUT_VAT_TAX_ENABLE = 'gomage_light_checkout_configuration/vat/enable';
    const XML_PATH_LIGHT_CHECKOUT_VAT_TAX_VERIFICATION_SYSTEM = 'gomage_light_checkout_configuration/vat/verification_system';
    const XML_PATH_LIGHT_CHECKOUT_VAT_TAX_BASE_EU_COUNTRY = 'gomage_light_checkout_configuration/vat/base_eu_country';
    const XML_PATH_LIGHT_CHECKOUT_VAT_TAX_B2C_B2B_BASE_EU = 'gomage_light_checkout_configuration/vat/b2c_b2b_base_eu';
    const XML_PATH_LIGHT_CHECKOUT_VAT_TAX_B2C_B2B_NOT_BASE_EU = 'gomage_light_checkout_configuration/vat/b2c_b2b_not_base_eu';
    const XML_PATH_LIGHT_CHECKOUT_VAT_TAX_RULE = 'gomage_light_checkout_configuration/vat/vat_tax_rule';
    const XML_PATH_LIGHT_CHECKOUT_VAT_TAX_SHOW_BUY_WITHOUT_VAT_CHECKBOX = 'gomage_light_checkout_configuration/vat/show_buy_without_vat_checkbox';
    const XML_PATH_LIGHT_CHECKOUT_VAT_TAX_TEXT_UNDER_VAT_TAX_FIELD = 'gomage_light_checkout_configuration/vat/text_under_vat_tax_field';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Terms and Conditions.
     */
    const XML_PATH_LIGHT_CHECKOUT_TERMS_AND_CONDITIONS_ENABLE = 'gomage_light_checkout_configuration/terms_and_conditions/enable';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Address Fields Required.
     */
    const XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_FIRST_NAME = 'gomage_light_checkout_configuration/checkout_address_fields_required/firstname';
    const XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_LAST_NAME = 'gomage_light_checkout_configuration/checkout_address_fields_required/lastname';
    const XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_COMPANY = 'gomage_light_checkout_configuration/checkout_address_fields_required/company';
    const XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_COUNTRY = 'gomage_light_checkout_configuration/checkout_address_fields_required/country_id';
    const XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_CITY = 'gomage_light_checkout_configuration/checkout_address_fields_required/city';
    const XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_STREET_ADDRESS = 'gomage_light_checkout_configuration/checkout_address_fields_required/street';
    const XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_ZIPCODE = 'gomage_light_checkout_configuration/checkout_address_fields_required/postcode';
    const XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_STATE = 'gomage_light_checkout_configuration/checkout_address_fields_required/region_id';
    const XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_PHONE = 'gomage_light_checkout_configuration/checkout_address_fields_required/telephone';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Address Fields Form.
     */
    const XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_FORM = 'gomage_light_checkout_configuration/checkout_address_fields_sorting/fields_form';
    const XML_PATH_LIGHT_CHECKOUT_ADDRESS_KEEP_FIELDS_INSIDE = 'gomage_light_checkout_configuration/checkout_address_fields_sorting/keep_field_names_inside';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Autofill by Zip Code.
     */
    const XML_PATH_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_ENABLE = 'gomage_light_checkout_configuration/autofill_by_zipcode/enable';
    const XML_PATH_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_ENABLE_ZIP_CACHING = 'gomage_light_checkout_configuration/autofill_by_zipcode/enabled_zip_caching';
    const XML_PATH_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_GOOGLE_API_KEY = 'gomage_light_checkout_configuration/autofill_by_zipcode/google_api_key';
    const XML_PATH_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_API_MODE = 'gomage_light_checkout_configuration/autofill_by_zipcode/api_mode';
    const XML_PATH_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_DISABLE_ADDRESS_FIELDS = 'gomage_light_checkout_configuration/autofill_by_zipcode/disable_address_fields';
    /**#@-*/

    /**#@+
     * Light Checkout configuration AutoComplete Street.
     */
    const XML_PATH_LIGHT_CHECKOUT_AUTO_COMPLETE_BY_STREET_ENABLE = 'gomage_light_checkout_configuration/auto_complete_by_street/enable';
    const XML_PATH_LIGHT_CHECKOUT_AUTO_COMPLETE_BY_STREET_GOOGLE_API_KEY = 'gomage_light_checkout_configuration/auto_complete_by_street/google_api_key';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Help Messages.
     */
    const XML_PATH_LIGHT_CHECKOUT_HELP_MESSAGES = 'gomage_light_checkout_configuration/help_messages/message';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Trust Seals.
     */
    const XML_PATH_LIGHT_CHECKOUT_TRUST_SEALS_ENABLED = 'gomage_light_checkout_configuration/trust_seals/enable';
    const XML_PATH_LIGHT_CHECKOUT_TRUST_SEALS_SEALS = 'gomage_light_checkout_configuration/trust_seals/seals';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Social Login Facebook.
     */
    const XML_PATH_LIGHT_CHECKOUT_SOCIAL_FACEBOOK_ENABLE = 'gomage_light_checkout_configuration/social_media_login/enable_facebook';
    const XML_PATH_LIGHT_CHECKOUT_SOCIAL_FACEBOOK_APP_ID = 'gomage_light_checkout_configuration/social_media_login/app_id_facebook';
    const XML_PATH_LIGHT_CHECKOUT_SOCIAL_FACEBOOK_APP_SECRET = 'gomage_light_checkout_configuration/social_media_login/app_secret_facebook';
    const XML_PATH_LIGHT_CHECKOUT_SOCIAL_FACEBOOK_REDIRECT_URL = 'gomage_light_checkout_configuration/social_media_login/redirect_url_facebook';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Social Login Google.
     */
    const XML_PATH_LIGHT_CHECKOUT_SOCIAL_GOOGLE_ENABLE = 'gomage_light_checkout_configuration/social_media_login/enable_google';
    const XML_PATH_LIGHT_CHECKOUT_SOCIAL_GOOGLE_APP_ID = 'gomage_light_checkout_configuration/social_media_login/app_id_google';
    const XML_PATH_LIGHT_CHECKOUT_SOCIAL_GOOGLE_APP_SECRET = 'gomage_light_checkout_configuration/social_media_login/app_secret_google';
    const XML_PATH_LIGHT_CHECKOUT_SOCIAL_GOOGLE_REDIRECT_URL = 'gomage_light_checkout_configuration/social_media_login/redirect_url_google';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Social Login Twitter.
     */
    const XML_PATH_LIGHT_CHECKOUT_SOCIAL_TWITTER_ENABLE = 'gomage_light_checkout_configuration/social_media_login/enable_twitter';
    const XML_PATH_LIGHT_CHECKOUT_SOCIAL_TWITTER_APP_ID = 'gomage_light_checkout_configuration/social_media_login/app_id_twitter';
    const XML_PATH_LIGHT_CHECKOUT_SOCIAL_TWITTER_APP_SECRET = 'gomage_light_checkout_configuration/social_media_login/app_secret_twitter';
    const XML_PATH_LIGHT_CHECKOUT_SOCIAL_TWITTER_REDIRECT_URL = 'gomage_light_checkout_configuration/social_media_login/redirect_url_twitter';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Subscribe To Newsletter.
     */
    const XML_PATH_LIGHT_CHECKOUT_NEWSLETTER_CHECKBOX_ENABLE = 'gomage_light_checkout_configuration/newsletter_checkbox/enable';
    const XML_PATH_LIGHT_CHECKOUT_NEWSLETTER_CHECKBOX_CHECKBOX_IS_CHECKED = 'gomage_light_checkout_configuration/newsletter_checkbox/checkbox_is_checked';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Color Settings.
     */
    const XML_PATH_LIGHT_CHECKOUT_COLOR_SETTINGS_PLACE_ORDER_BUTTON = 'gomage_light_checkout_configuration/color_settings/place_order_button';
    const XML_PATH_LIGHT_CHECKOUT_COLOR_SETTINGS_PLACE_ORDER_BUTTON_HOVER = 'gomage_light_checkout_configuration/color_settings/place_order_button_hover';
    const XML_PATH_LIGHT_CHECKOUT_COLOR_SETTINGS_CHECKOUT_COLOR = 'gomage_light_checkout_configuration/color_settings/checkout_color';
    /**#@-*/

    /**#@+
     * Light Checkout configuration Number of products visible in checkout.
     */
    const XML_PATH_LIGHT_CHECKOUT_NUMBER_OF_PRODUCTS_HIDE = 'gomage_light_checkout_configuration/number_product_in_checkout/hide_products_in_total_order_block';
    const XML_PATH_LIGHT_CHECKOUT_NUMBER_OF_PRODUCTS_VISIBLE_IN_CHECKOUT = 'gomage_light_checkout_configuration/number_product_in_checkout/number_visible_in_checkout';
    /**#@-*/
    // @codingStandardsIgnoreEnd

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

    public function getIsEnabledGeoIp()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_GEOIP_ENABLE);
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

    public function getIsRequiredAddressFieldFirstName()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_FIRST_NAME);
    }

    public function getIsRequiredAddressFieldLastName()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_LAST_NAME);
    }

    public function getIsRequiredAddressFieldCompany()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_COMPANY);
    }

    public function getIsRequiredAddressFieldCountry()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_COUNTRY);
    }

    public function getIsRequiredAddressFieldCity()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_CITY);
    }

    public function getIsRequiredAddressFieldStreetAddress()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_STREET_ADDRESS);
    }

    public function getIsRequiredAddressFieldZipPostalCode()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_ZIPCODE);
    }

    public function getIsRequiredAddressFieldStateProvince()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_STATE);
    }

    public function getIsRequiredAddressFieldPhoneNumber()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_REQUIRED_PHONE);
    }

    public function getAddressFieldsForm()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_ADDRESS_FIELDS_FORM);
    }

    public function getAddressFieldsKeepInside()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_ADDRESS_KEEP_FIELDS_INSIDE);
    }

    public function getIsEnabledAutoFillByZipCode()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_ENABLE);
    }

    public function getAutoFillByZipCodeIsEnabledZipCaching()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_ENABLE_ZIP_CACHING);
    }

    public function getAutoFillByZipCodeGoogleApiKey()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_GOOGLE_API_KEY);
    }

    public function getAutoFillByZipCodeApiMode()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_API_MODE);
    }

    public function getAutoFillByZipCodeIsDisabledAddressFields()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_AUTOFILL_BY_ZIP_CODE_DISABLE_ADDRESS_FIELDS);
    }

    public function getHelpMessages()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_HELP_MESSAGES);
    }

    public function getIsEnabledAutoCompleteByStreet()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_AUTO_COMPLETE_BY_STREET_ENABLE);
    }

    public function getAutoCompleteByStreetGoogleApiKey()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_AUTO_COMPLETE_BY_STREET_GOOGLE_API_KEY);
    }

    public function getIsEnabledTrustSeals()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_TRUST_SEALS_ENABLED);
    }

    public function getTrustSealsSeals()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_TRUST_SEALS_SEALS);
    }

    public function getIsSocialLoginFacebookEnabled()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_SOCIAL_FACEBOOK_ENABLE);
    }

    public function getSocialLoginFacebookAppId()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_SOCIAL_FACEBOOK_APP_ID);
    }

    public function getSocialLoginFacebookAppSecret()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_SOCIAL_FACEBOOK_APP_SECRET);
    }

    public function getSocialLoginFacebookAppRedirectUrl()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_SOCIAL_FACEBOOK_REDIRECT_URL);
    }

    public function getIsSocialLoginGoogleEnabled()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_SOCIAL_GOOGLE_ENABLE);
    }

    public function getSocialLoginGoogleAppId()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_SOCIAL_GOOGLE_APP_ID);
    }

    public function getSocialLoginGoogleAppSecret()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_SOCIAL_GOOGLE_APP_SECRET);
    }

    public function getSocialLoginGoogleAppRedirectUrl()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_SOCIAL_GOOGLE_REDIRECT_URL);
    }

    public function getIsSocialLoginTwitterEnabled()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_SOCIAL_TWITTER_ENABLE);
    }

    public function getSocialLoginTwitterAppId()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_SOCIAL_TWITTER_APP_ID);
    }

    public function getSocialLoginTwitterAppSecret()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_SOCIAL_TWITTER_APP_SECRET);
    }

    public function getSocialLoginTwitterAppRedirectUrl()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_SOCIAL_TWITTER_REDIRECT_URL);
    }

    public function getIsEnabledSubscribeToNewsletter()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_NEWSLETTER_CHECKBOX_ENABLE);
    }

    public function getSubscribeToNewsletterIsCheckboxChecked()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_NEWSLETTER_CHECKBOX_CHECKBOX_IS_CHECKED);
    }

    public function getCheckoutColorSettingsPlaceOrderButton()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_COLOR_SETTINGS_PLACE_ORDER_BUTTON);
    }

    public function getCheckoutColorSettingsPlaceOrderButtonHover()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_COLOR_SETTINGS_PLACE_ORDER_BUTTON_HOVER);
    }

    public function getCheckoutColorSettingsCheckoutColor()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_COLOR_SETTINGS_CHECKOUT_COLOR);
    }

    public function getIsHidedNumberOfProducts()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_NUMBER_OF_PRODUCTS_HIDE);
    }

    public function getNumberOfProductsVisibleInCheckout()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LIGHT_CHECKOUT_NUMBER_OF_PRODUCTS_VISIBLE_IN_CHECKOUT);
    }
}
