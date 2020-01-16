<?php


namespace Mageplaza\GiftCard\Observer;


use Magento\Framework\Event\Observer;
use Mageplaza\GiftCard\Model\ResourceModel\History\CollectionFactory;

class CheckCode implements \Magento\Framework\Event\ObserverInterface
{
    protected $_giftCardFactory;
    protected $redirect;
    protected $_actionFlag;
    protected $messageManager;
    protected $_checkoutSession;
    protected $_currentCustomer;
    protected $_historyCollectionFactory;
    protected $_giftcardModelFactory;

    function __construct(
        \Mageplaza\GiftCard\Model\ResourceModel\History\CollectionFactory $historyCollectionFactory,
        \Mageplaza\GiftCard\Model\GiftCardFactory $GiftCardFactory,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Magento\Framework\App\ActionFlag $actionFlag,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory
    )
    {
        $this->_giftcardModelFactory = $giftCardFactory;
        $this->_historyCollectionFactory = $historyCollectionFactory;
        $this->_currentCustomer = $currentCustomer;
        $this->redirect = $redirect;
        $this->_giftCardFactory = $GiftCardFactory;
        $this->_actionFlag = $actionFlag;
        $this->messageManager = $messageManager;
        $this->_checkoutSession = $checkoutSession;
    }

    public function execute(Observer $observer)
    {
        $this->checkCode($observer);
    }

    public function checkCode(Observer $observer)
    {

        $_currentCustomer_id = $this->_currentCustomer->getCustomerId();
        $controller = $observer->getData('controller_action');
        $_coupon_code_array = $controller->getRequest()->getParams();


        if ($_coupon_code_array['remove'] == 0) {

            $_giftcard_id = $this->_giftcardModelFactory->create()->load($_coupon_code_array['coupon_code'], 'code')->getData('giftcard_id');
            $_giftcardHistory = $this->_historyCollectionFactory->create()->addFieldToFilter('giftcard_id', $_giftcard_id)->getData();
            $_giftcard_code = $this->_giftCardFactory->create()->load($_coupon_code_array['coupon_code'], 'code')->getData('code');

            if (empty($_giftcard_code)) {
                return ;
            }

            foreach ($_giftcardHistory as $key => $value) {
                if ($_currentCustomer_id == $value['customer_id']) {
                    if ($_coupon_code_array['coupon_code'] == $_giftcard_code) {
                        $this->messageManager->addSuccessMessage(__('Gift code applied successfully'));
                        $this->_checkoutSession->setCode($_coupon_code_array['coupon_code']);
                        $this->_actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
                    }
                } else {
                    $this->messageManager->addErrorMessage(__('Gift code not available for this user'));
                    $this->_actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
                }
            }
        } else {
            $this->_checkoutSession->unsCode();
            $this->messageManager->addSuccessMessage(__('Gift code is canceled'));
            $this->_actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
        }
        $this->redirect->redirect($controller->getResponse(), 'checkout/cart');
    }
}