<?php

namespace GoMage\LightCheckout\Controller\Adminhtml\Config;

use GoMage\LightCheckout\Model\PostCode\EmptyCollection;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;

class ClearPostcodeCache extends Action
{
    /**
     * @type JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var EmptyCollection
     */
    private $emptyCollection;

    /**
     * @param Action\Context $context
     * @param JsonFactory $resultJsonFactory
     * @param EmptyCollection $emptyCollection
     */
    public function __construct(
        Action\Context $context,
        JsonFactory $resultJsonFactory,
        EmptyCollection $emptyCollection
    ) {
        parent::__construct($context);

        $this->resultJsonFactory = $resultJsonFactory;
        $this->emptyCollection = $emptyCollection;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $success = true;
        $message = __('Cache was successfully cleared');

        try {
            /** @var \Magento\Framework\Controller\Result\Json $result */
            $result = $this->resultJsonFactory->create();
        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        $this->emptyCollection->execute();

        return $result->setData(['success' => $success, 'message' => $message]);
    }
}
