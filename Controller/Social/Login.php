<?php

namespace GoMage\LightCheckout\Controller\Social;

use GoMage\LightCheckout\Model\SocialManagement;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\RawFactory;

class Login extends Action
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var RawFactory
     */
    private $resultRawFactory;

    /**
     * @var SocialManagement
     */
    private $socialManagement;

    /**
     * @param Context $context
     * @param Session $session
     * @param RawFactory $rawFactory
     * @param SocialManagement $socialManagement
     */
    public function __construct(
        Context $context,
        Session $session,
        RawFactory $rawFactory,
        SocialManagement $socialManagement
    ) {
        parent::__construct($context);

        $this->session = $session;
        $this->resultRawFactory = $rawFactory;
        $this->socialManagement = $socialManagement;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        if ($this->session->isLoggedIn()) {
            return $this->_redirect('checkout');
        }

        $type = $this->getRequest()->getParam('type', null);
        if ($type === null) {
            $this->_forward('noroute');

            return $this;
        }

        try {
            $userProfile = $this->socialManagement->getUserProfileByType($type);

            if (!$userProfile->identifier) {
                $this->messageManager->addErrorMessage(__('Please enter email in your %1 profile', $type));

                return $this->_redirect('checkout');
            }
        } catch (\Exception $e) {
            $this->getResponse()->setBody(__('Error: ') . $e->getMessage());

            return $this;
        }

        $this->socialManagement->login($userProfile, $type);

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();

        return $resultRaw->setContents(
            sprintf("<script>window.opener.socialCallback('%s', window);</script>", $this->_url->getUrl('checkout'))
        );
    }
}
