<?php

namespace GoMage\LightCheckout\Model\CheckVatNumber;

class ViesChecker
{
    /**
     * @param string $country
     * @param string $vatNumber
     *
     * @return bool
     */
    public function execute($country, $vatNumber)
    {
        $check = new \SoapClient('http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl');
        $content = $check->checkVat(['countryCode' => $country, 'vatNumber' => $vatNumber]);
        $isValidVat = $content->valid;

        return $isValidVat;
    }
}
