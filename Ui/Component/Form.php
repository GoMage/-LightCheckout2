<?php


namespace GoMage\LightCheckout\Ui\Component;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Form as FormComponent;

class Form extends FormComponent
{
    public function __construct(ContextInterface $context, FilterBuilder $filterBuilder, array $components = [], array $data = [])
    {
        parent::__construct($context, $filterBuilder, $components, $data);
    }

    /**
     * @inheritdoc
     */
    public function getDataSourceData()
    {
        return ['data' => $this->getContext()->getDataProvider()->getData()];
    }
}
