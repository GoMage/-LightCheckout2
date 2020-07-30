<?php

namespace GoMage\LightCheckout\Plugin\Customer\Model\Attribute\Data;

use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;
use GoMage\LightCheckout\Model\IsEnableLightCheckout;
use Magento\Customer\Model\Attribute\Data\Postcode;

/**
 * Class ValidatePostcode
 * @package GoMage\LightCheckout\Plugin\Customer\Model\Attribute\Data
 */
class ValidatePostcode
{
    /**
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @var IsEnableLightCheckout
     */
    private $isEnableLightCheckout;

    /**
     * ValidatePostcode constructor.
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param IsEnableLightCheckout $isEnableLightCheckout
     */
    public function __construct(
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        IsEnableLightCheckout $isEnableLightCheckout
    ) {
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->isEnableLightCheckout = $isEnableLightCheckout;
    }

    /**
     * @param Postcode $subject
     * @param $result
     * @param $value
     * @return array|bool
     * @throws \Zend_Validate_Exception
     */
    public function afterValidateValue(
        Postcode $subject,
        $result,
        $value
    ) {
        if ($this->isEnableLightCheckout->execute()) {
            $errors = [];

            $isZipRequired = (bool)$this->checkoutConfigurationsProvider->getIsRequiredAddressFieldZipPostalCode();
            if ($isZipRequired && !\Zend_Validate::is($value, 'NotEmpty')
            ) {
                $errors[] = __('Please enter the zip/postal code.');
            }

            if (empty($errors)) {
                return true;
            }

            return $errors;
        } else {
            return $result;
        }
    }
}
