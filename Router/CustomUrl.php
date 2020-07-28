<?php
declare(strict_types=1);

namespace GoMage\LightCheckout\Router;

use GoMage\LightCheckout\Model\Config\CheckoutConfigurationsProvider;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\App\Router\DefaultRouter;
use Magento\Framework\App\Router\Base as StandardRouter;
use GoMage\LightCheckout\Model\IsEnableLightCheckout;

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
     * @var DefaultRouter
     */
    private $defaultRouter;

    /**
     * @var StandardRouter
     */
    private $standardRouter;

    /**
     * @var IsEnableLightCheckout
     */
    private $isEnableLightCheckout;

    /**
     * CustomUrl constructor.
     * @param CheckoutConfigurationsProvider $checkoutConfigurationsProvider
     * @param DefaultRouter $defaultRouter
     * @param StandardRouter $standardRouter
     * @param IsEnableLightCheckout $isEnableLightCheckout
     */
    public function __construct(
        CheckoutConfigurationsProvider $checkoutConfigurationsProvider,
        DefaultRouter $defaultRouter,
        StandardRouter $standardRouter,
        IsEnableLightCheckout $isEnableLightCheckout
    )
    {
        $this->checkoutConfigurationsProvider = $checkoutConfigurationsProvider;
        $this->defaultRouter = $defaultRouter;
        $this->standardRouter = $standardRouter;
        $this->isEnableLightCheckout = $isEnableLightCheckout;
    }

    /**
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface|null
     */
    public function match(RequestInterface $request)
    {
        $identifier = trim($request->getPathInfo(), '/');
        $customUrl = $this->checkoutConfigurationsProvider->getLightCheckoutUrl();

        if ($identifier && $customUrl && $identifier === $customUrl
            && $this->isEnableLightCheckout->execute()) {
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
