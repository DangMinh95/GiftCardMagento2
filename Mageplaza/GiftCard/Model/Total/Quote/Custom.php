<?php


namespace Mageplaza\GiftCard\Model\Total\Quote;


class Custom extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    protected $_priceCurrency;
    protected $_checkoutSession;
    protected $_giftCardFactory;
    protected $_priceHelper;
    protected $_value;

    public function __construct(
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory,
        \Magento\Framework\Pricing\Helper\Data $priceHelper
    )
    {
        $this->_priceHelper = $priceHelper;
        $this->_priceCurrency = $priceCurrency;
        $this->_checkoutSession = $checkoutSession;
        $this->_giftCardFactory = $giftCardFactory;
    }

    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        parent::collect($quote, $shippingAssignment, $total);


        $_giftcardCode = $this->_checkoutSession->getCode();

        $baseDiscount = $this->_giftCardFactory->create()->load($_giftcardCode, 'code')->getBalance();

        $_subTotal = $total->getSubtotal();

        if ($baseDiscount > $_subTotal) {
            $baseDiscount = $_subTotal;
        }
        $this->_value = $baseDiscount;

        echo $this->_value . '<br>';

        $discount = $this->_priceCurrency->convert($baseDiscount);
        $total->addTotalAmount('customdiscount', -$discount);
        $total->addBaseTotalAmount('customdiscount', -$baseDiscount);
        $total->setBaseGrandTotal($total->getBaseGrandTotal() - $baseDiscount);
        $quote->setCustomDiscount(-$discount);

        return $this;
    }

    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        return [
            'code' => 'customer_discount',
            'title' => __('Giftcard Discount (' . $this->_value . ')'),
            'value' => $this->_value
        ];
    }

}