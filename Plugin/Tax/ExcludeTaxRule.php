<?php

namespace GoMage\LightCheckout\Plugin\Tax;

use Magento\Checkout\Model\Session;
use GoMage\LightCheckout\Model\IsEnableLightCheckout;

class ExcludeTaxRule
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var IsEnableLightCheckout
     */
    private $isEnableLightCheckout;

    /**
     * @param Session $session
     * @param IsEnableLightCheckout $isEnableLightCheckout
     */
    public function __construct(
        Session $session,
        IsEnableLightCheckout $isEnableLightCheckout
    ) {
        $this->session = $session;
        $this->isEnableLightCheckout = $isEnableLightCheckout;
    }

    /**
     * @param $subject
     * @param array $result
     *
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetCalculationProcess($subject, $result)
    {
        if ($this->isEnableLightCheckout->execute()) {
            $ruleIdsString = $this->session->getData('light_checkout_exclude_tax_rule_ids');
            if ($ruleIdsString) {
                $ruleIds = explode(',', $ruleIdsString);

                if ($ruleIds) {
                    foreach ($result as $key => $taxes) {
                        foreach ($taxes['rates'] as $rate) {
                            if (in_array($rate['rule_id'], $ruleIds)) {
                                unset($result[$key]);
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }
}
