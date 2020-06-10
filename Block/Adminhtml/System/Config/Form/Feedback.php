<?php

namespace GoMage\LightCheckout\Block\Adminhtml\System\Config\Form;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Feedback
 * @package GoMage\LightCheckout\Block\Adminhtml\System\Config\Form
 */
class Feedback extends Field
{
    /**
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        return $this->getModuleInfoHtml();
    }

    /**
     * @return string
     */
    public function getModuleInfoHtml()
    {

        $message = 'Want to report a bug, ask for a new feature or customize the extension per your needs? <br>Please submit a <a href="https://www.gomage.com/contacts/" target="_blank" style="color:#57d68d; "><b>support request</b></a> and we will find the best solution for you.';


        $html = '<tr><td class="label" colspan="4" style="text-align: left;"><div style="padding:10px;background-color:#f8f8f8;border:1px solid #dddddd;margin-bottom:7px;">
            ' . $message . '</div></td></tr>';


        return $html;
    }


}
