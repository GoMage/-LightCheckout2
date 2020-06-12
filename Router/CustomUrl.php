<?php
declare(strict_types=1);

namespace GoMage\LightCheckout\Router;

use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;
use GoMage\Core\Helper\Data as CoreHelper;
use GoMage\LightCheckout\Setup\InstallData;
use GoMage\LightCheckout\Model\IsEnableLightCheckoutForDevice;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\App\Router\DefaultRouter;
use Magento\Framework\App\Router\Base as StandardRouter;

/**
 * Class CustomUrl
 * @package GoMage\LightCheckout\Router
 */
class CustomUrl implements RouterInterface
{
    /**
     * @var CheckoutConfigurationsProvider
     */
    private $checkoutConfigurationsProvider;

    /**
     * @var CoreHelper
     */
    private $helper;

    /**
     * @var IsEnableLightCheckoutForDevice
     */
    private $isEnableLightCheckoutForDevice;

    /**
     * @var DefaultRouter
     */
    private $defaultRouter;

    /**
     * @var StandardRouter
     */
    private $standardRouter;

    /**
     * CustomUrl constructor.
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param CoreHelper $helper
     * @param IsEnableLightCheckoutForDevice $isEnableLightCheckoutForDevice
     * @param DefaultRouter $defaultRouter
     * @param StandardRouter $standardRouter
     */
    public function __construct(
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        CoreHelper $helper,
        IsEnableLightCheckoutForDevice $isEnableLightCheckoutForDevice,
        DefaultRouter $defaultRouter,
        StandardRouter $standardRouter
    )
    {
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->helper = $helper;
        $this->isEnableLightCheckoutForDevice = $isEnableLightCheckoutForDevice;
        $this->defaultRouter = $defaultRouter;
        $this->standardRouter = $standardRouter;
    }

    /**
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface|null
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function match(RequestInterface $request)
    {
        $identifier = trim($request->getPathInfo(), '/');
        $customUrl = $this->checkoutConfigurationsProvider->getLightCheckoutUrl();

        if ($identifier == $customUrl && $this->helper->isA(InstallData::MODULE_NAME)
            && $this->checkoutConfigurationsProvider->isLightCheckoutEnabled()
            && $this->isEnableLightCheckoutForDevice->execute()) {
            $request->setModuleName('lightcheckout');
            $request->setControllerName('index');
            $request->setActionName('index');
            return $this->standardRouter->match($request);
        } elseif (('checkout' == $identifier || 'checkout/index' == $identifier || 'checkout/index/index' == $identifier)
            && $customUrl) {
            $this->defaultRouter->match($request);
        }
        return null;
    }
}
