<?php

namespace GoMage\LightCheckout\Model\SocialCustomer;

use Magento\Framework\Url;
use Magento\Store\Model\StoreManagerInterface;

class BaseAuthUrlProvider
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Url
     */
    private $urlBuilder;

    /**
     * @param StoreManagerInterface $storeManager
     * @param Url $urlBuilder
     */
    public function __construct(StoreManagerInterface $storeManager, Url $urlBuilder)
    {
        $this->storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @return string
     */
    public function get()
    {
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->storeManager->getStore();

        $url = $this->urlBuilder->getUrl(
            'lightcheckout/social/callback',
            [
                '_nosid' => true,
                '_scope' => $store->getId(),
                '_secure' => $store->isUrlSecure()
            ]
        );

        return $url;
    }
}
