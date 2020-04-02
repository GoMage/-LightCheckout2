<?php

namespace GoMage\LightCheckout\Api;

interface GetAddressByPostCodeInterface
{
    /**
     * @param string $postcode
     *
     * @return \GoMage\LightCheckout\Api\Data\GetAddressByPostCode\ResponseDataInterface
     */
    public function execute($postcode);
}
