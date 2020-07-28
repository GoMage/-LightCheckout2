<?php

namespace GoMage\LightCheckout\Observer;

use GoMage\LightCheckout\Model\IsEnableLightCheckout;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote;
use Magento\Sales\Api\Data\OrderInterface;

class CommentOrderFromQuoteToOrder implements ObserverInterface
{

    /**
     * @var IsEnableLightCheckout
     */
    private $isEnableLightCheckout;

    /**
     * @param IsEnableLightCheckout $isEnableLightCheckout
     */
    public function __construct(
        IsEnableLightCheckout $isEnableLightCheckout
    ) {
        $this->isEnableLightCheckout = $isEnableLightCheckout;
    }

    /**
     * @inheritdoc
     */
    public function execute(Observer $observer)
    {
        if ($this->isEnableLightCheckout->execute()) {
            $event = $observer->getEvent();

            /** @var OrderInterface $order */
            $order = $event->getOrder();

            /** @var Quote $quote */
            $quote = $event->getQuote();

            $order->setCommentOrder($quote->getCommentOrder());
            return $this;
        }
    }
}
