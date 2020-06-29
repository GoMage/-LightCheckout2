<?php

namespace GoMage\LightCheckout\Api\Data\QuoteItemManagement;

use Magento\Quote\Model\Quote;

interface ResponseDataInterface
{
    const SHIPPING_METHODS = 'shipping_methods';
    const PAYMENT_METHODS = 'payment_methods';
    const TOTALS = 'totals';
    const REDIRECT_URL = 'redirect_url';
    const ERROR = 'error';
    const QUOTE_TYPE = 'quote_type';

    /**
     * Get shipping methods.
     *
     * @return \Magento\Quote\Api\Data\ShippingMethodInterface[]
     */
    public function getShippingMethods();

    /**
     * Set shipping methods for response.
     *
     * @param \Magento\Quote\Api\Data\ShippingMethodInterface[] $shippingMethods
     *
     * @return $this
     */
    public function setShippingMethods($shippingMethods);

    /**
     * Get payment methods.
     *
     * @return \Magento\Quote\Api\Data\PaymentMethodInterface[]
     */
    public function getPaymentMethods();

    /**
     * Set payment methods.
     *
     * @param \Magento\Quote\Api\Data\PaymentMethodInterface[] $paymentMethods
     *
     * @return $this
     */
    public function setPaymentMethods($paymentMethods);

    /**
     * Get totals.
     *
     * @return \Magento\Quote\Api\Data\TotalsInterface
     */
    public function getTotals();

    /**
     * Set totals.
     *
     * @param \Magento\Quote\Api\Data\TotalsInterface $totals
     *
     * @return $this
     */
    public function setTotals($totals);

    /**
     * Get redirect url.
     *
     * @return string
     */
    public function getRedirectUrl();

    /**
     * Set redirect url.
     *
     * @param $url
     *
     * @return $this
     */
    public function setRedirectUrl($url);

    /**
     * @return mixed
     */
    public function getError();

    /**
     * @param $error
     * @return mixed
     */
    public function setError($error);

    /**
     * @return string
     */
    public function getQuoteType();

    /**
     * @param Quote $quote
     * @return $this
     */
    public function setQuoteType(Quote $quote);
}
