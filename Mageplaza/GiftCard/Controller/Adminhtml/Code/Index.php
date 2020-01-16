<?php

namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;

class Index extends \Magento\Backend\App\Action
{

    const MANAGE_CODES_ROLE = 'Mageplaza_GiftCard::giftcard';

    protected $resultPageFactory = false;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('Gift Card')));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::MANAGE_CODES_ROLE);
    }
}