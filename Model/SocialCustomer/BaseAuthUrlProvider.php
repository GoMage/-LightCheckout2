<?php

namespace GoMage\LightCheckout\Model\SocialCustomer;

use Magento\Framework\Url;
use Magento\Store\Model\StoreManagerInterface;

class BaseAuthUrlProvider
{

    /**
     * @var Url
     */
    private $urlBuilder;

    /**
     * BaseAuthUrlProvider constructor.
     * @param Url $urlBuilder
     */
    public function __construct(Url $urlBuilder)
    {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param $type
     * @return mixed
     */
    public function get($type)
    {
        $url = $this->urlBuilder->getUrl(
            'lightcheckout/social/login',
            [
                'type' => $type,
            ]
        );
        return $url;
    }
}
