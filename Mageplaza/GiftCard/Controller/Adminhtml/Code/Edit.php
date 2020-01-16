<?php


namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;


use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;

class Edit extends Action
{
    protected $_resultPageFactory;
    protected $_GiftCardFactory;
    protected $_giftRegistry;

    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\App\Action\Context $context,
        \Mageplaza\GiftCard\Model\GiftCardFactory $GiftCardFactory,
        \Magento\Framework\Registry $registry
    )
    {
        $this->_giftRegistry = $registry;
        $this->_GiftCardFactory = $GiftCardFactory;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $_gift_card_model = $this->_GiftCardFactory->create();
        $_gift_card_model_id = $_gift_card_model->load($id);
        $this->_giftRegistry->register('giftcard', $_gift_card_model_id);
        $_resultPage = $this->_resultPageFactory->create();
        return $_resultPage;
    }
}