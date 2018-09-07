<?php

namespace GoMage\LightCheckout\Model\Config\Backend\DeliveryDate;

use Magento\Framework\App\Config\Value;

class Days extends Value
{
    /**
     * @inheritdoc
     */
    public function beforeSave()
    {
        $result = [];
        $value = $this->getValue();
        foreach ($value as $data) {
            if (is_array($data)) {
                $result[] = $data;
            }
        }
        $this->setValue(serialize($result));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function afterLoad()
    {
        $value = @unserialize($this->getValue());
        if (is_array($value)) {
            $this->setValue($value);
        }

        return $this;
    }
}
