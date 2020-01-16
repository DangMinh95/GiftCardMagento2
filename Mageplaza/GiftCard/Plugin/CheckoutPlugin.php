<?php


namespace Mageplaza\GiftCard\Plugin;

use Mageplaza\GiftCard\Observer\CheckCode;

class CheckoutPlugin
{
    protected $_checkoutSession;
    protected $_giftcardModelFactory;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory)
    {
        $this->_giftcardModelFactory = $giftCardFactory;
        $this->_checkoutSession = $checkoutSession;
    }

    public function afterGetCouponCode(\Magento\Checkout\Block\Cart\Coupon $subject, $result)
    {
        $code = $this->_checkoutSession->getCode();
        $_giftcardCode = $this->_giftcardModelFactory->create()->load($code,'code')->getData('code');

        if(empty($_giftcardCode)){
            return $result;
        }else{
            return $result . $code;
        }
    }
}