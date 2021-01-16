<?php

namespace GoMage\LightCheckout\Block\Adminhtml\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class FieldsManager extends Field
{
    /**
     * @var string
     */
    protected $_template = 'GoMage_LightCheckout::config/fields-manager.phtml';

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }
}
