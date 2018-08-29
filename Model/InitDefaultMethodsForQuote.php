<?php

namespace GoMage\LightCheckout\Model;

use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Locale\Resolver;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Add to quote default methods from config.
 */
class InitDefaultMethodsForQuote
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var DirectoryHelper
     */
    private $directoryHelper;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Resolver
     */
    private $localResolver;

    /**
     * @param CartRepositoryInterface $cartRepository
     * @param DirectoryHelper $directoryHelper
     * @param StoreManagerInterface $storeManager
     * @param Resolver $localResolver
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        DirectoryHelper $directoryHelper,
        StoreManagerInterface $storeManager,
        Resolver $localResolver
    ) {
        $this->cartRepository = $cartRepository;
        $this->directoryHelper = $directoryHelper;
        $this->storeManager = $storeManager;
        $this->localResolver = $localResolver;
    }

    /**
     * @param $quote
     */
    public function execute(CartInterface $quote)
    {
        $shippingAddress = $quote->getShippingAddress();
        if (!$shippingAddress->getCountryId()) {
            $defaultCountryId = $this->directoryHelper->getDefaultCountry();
            if (!$defaultCountryId) {
                $defaultCountryId = $this->getDefaultCountryIdFromLocale();
            }
            $shippingAddress->setCountryId($defaultCountryId)->setCollectShippingRates(true);
        }

        $this->cartRepository->save($quote);
    }

    /**
     * @return bool|string
     */
    private function getDefaultCountryIdFromLocale()
    {
        $locale = $this->localResolver->getLocale();

        return substr($locale, strrpos($locale, "_") + 1);
    }
}
