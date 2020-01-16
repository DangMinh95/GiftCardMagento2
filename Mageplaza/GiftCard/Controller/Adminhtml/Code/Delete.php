<?php


namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;


use Magento\Backend\App\Action;


class Delete extends Action
{
    protected $_giftcardFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory
    )
    {
        $this->_giftcardFactory = $giftCardFactory;
        parent::__construct($context);
    }

    public function execute()
    {

        $_param_selector = $this->getRequest()->getParam('selected');
        $_giftcard_model = $this->_giftcardFactory->create();

        for ($i = 0; $i < count($_param_selector); $i++) {
            $_giftcard_model->load($_param_selector[$i])->delete();
        }
        $this->_redirect('giftcard/code/index');

    }
}