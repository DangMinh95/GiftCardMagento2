<?php

namespace Mageplaza\GiftCard\Controller\Index;

use Magento\Framework\App\Action\Context;

class MyGiftCard extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_currentCustomer;
    protected $_customerSession;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Customer\Model\Session $session
    )

    {
        $this->_customerSession = $session;
        $this->_currentCustomer = $currentCustomer;
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        $_currentCustomerId = $this->_currentCustomer->getCustomerId();
        $_customerSession = $this->_customerSession;
        if ($_customerSession->isLoggedIn()) {
            return $this->_redirect('customer/account/login');
        } else {
            $resultPage = $this->_pageFactory->create();
            $resultPage->getConfig()->getTitle()->prepend(__('My Gift Card'));
            return $resultPage;
        }

    }
}