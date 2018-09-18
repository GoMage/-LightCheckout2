<?php

namespace GoMage\LightCheckout\Model\CheckVatNumber;

class IsvatChecker
{
    /**
     * @param string $country
     * @param string $vatNumber
     *
     * @return bool
     */
    public function execute($country, $vatNumber)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://isvat.appspot.com/' . $country . '/' . $vatNumber . '/');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        curl_close($ch);
        $isValidVat = !(strpos($content, "true") === false);

        return $isValidVat;
    }
}
