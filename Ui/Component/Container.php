<?php

namespace GoMage\LightCheckout\Ui\Component;

use Magento\Ui\Component\AbstractComponent;

class Container extends AbstractComponent
{
    const NAME = 'container';

    /**
     * Get component name
     *
     * @return string
     */
    public function getComponentName()
    {
        $type = $this->getData('type');
        return static::NAME . ($type ? ('.' . $type): '');
    }

    /**
     * @return array
     */
    public function getDataSourceData()
    {
        return ['data' => $this->getContext()->getDataProvider()->getData()];
    }
}
