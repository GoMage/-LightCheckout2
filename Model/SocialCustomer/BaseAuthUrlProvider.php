<?php

namespace GoMage\LightCheckout\Model\SocialCustomer;

use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class BaseAuthUrlProvider
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param StoreManagerInterface $storeManager
     * @param UrlInterface $urlBuilder
     */
    public function __construct(StoreManagerInterface $storeManager, UrlInterface $urlBuilder)
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

        return $this->urlBuilder->getUrl(
            'lightcheckout/social/callback',
            [
                '_nosid' => true,
                '_scope' => $store->getId(),
                '_secure' => $store->isUrlSecure()
            ]
        );
    }
}
