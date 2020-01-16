<?php


namespace Mageplaza\GiftCard\Controller\Customer;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Action\Context;
use Mageplaza\GiftCard\Model\HistoryFactory;
use Mageplaza\GiftCard\Model\GiftCardFactory;

class Redeem extends \Magento\Framework\App\Action\Action
{
    protected $_history_model;
    protected $_giftcard_model;
    protected $redirect;

    public function __construct(
        GiftCardFactory $giftcard_model,
        HistoryFactory $history,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Response\RedirectInterface $redirect
    )
    {
        $this->redirect = $redirect;
        $this->_history_model = $history;
        $this->_giftcard_model = $giftcard_model;
        return parent::__construct($context);
    }

    public function execute()
    {
        $this->updateRedeem();
        return $this->_redirect('giftcardname/index/mygiftcard');
    }

    protected function updateRedeem()
    {
        $_param_giftcard_code = $this->getRequest()->getParam('code');
        $_giftcard_model = $this->_giftcard_model->create();
        $_history_model = $this->_history_model->create();

        $_giftcard_id = $_giftcard_model->load($_param_giftcard_code, 'code')->getData('giftcard_id');
        $_history_action = $_history_model->load($_giftcard_id, 'giftcard_id')->getData('action');
        $_history_id = $_history_model->load($_giftcard_id, 'giftcard_id')->getData('history_id');

        if (isset($_giftcard_id)) {
            if ($_history_action != 'redeem') {
                $data = [
                    'history_id' => $_history_id,
                    'action' => 'redeem'
                ];
                $_history_model->setData($data)->save();
                $this->messageManager->addSuccessMessage('Da cap nhat');
            } else {
                $this->messageManager->addErrorMessage('Khong the redeem');
            }
        } else {
            $this->messageManager->addErrorMessage('Ma code khong ton tai');
        }

    }
}