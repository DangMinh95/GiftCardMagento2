<?php


namespace Mageplaza\GiftCard\Observer;

use Mageplaza\GiftCard\Controller\Index\Test;
use Magento\Framework\Event\Observer;
use mysql_xdevapi\Exception;

class BuyingProduct implements \Magento\Framework\Event\ObserverInterface
{
    protected $_product;
    protected $_helper_data;
    protected $_giftCardFactory;
    protected $_historyFactory;

    function __construct(
        \Magento\Catalog\Model\ProductFactory $product,
        \Mageplaza\GiftCard\Helper\Data $helperData,
        \Mageplaza\GiftCard\Model\GiftCardFactory $GiftCardFactory,
        \Mageplaza\GiftCard\Model\HistoryFactory $HistoryFactory
    )
    {
        $this->_historyFactory = $HistoryFactory;
        $this->_giftCardFactory = $GiftCardFactory;
        $this->_product = $product;
        $this->_helper_data = $helperData;
    }

    public function execute(Observer $observer)
    {
            $_lenght = $this->_helper_data->getGeneralConfig('code_length');
            $_order = $observer->getData('order');
            $_items = $_order->getItems();
            $_product = $this->_product->create();

            foreach ($_items as $key => $item) {

//            print_r($item->getData());
                $_giftCard = $this->_giftCardFactory->create();
                $_customer_id = $_order->getCustomerId();

                $_increment_id = $_order->getIncrementId();
                $_gift_card_amount = $_product->load($item['product_id'])->getGiftCardAmount();

                if ($item->getProductType() == 'virtual' && $_gift_card_amount >=0) {
                    for ($i = 0; $i < $item['qty_ordered']; $i++) {

                        $_rand = $this->randomLenght($_lenght);
                        $this->insertDataBaseGiftCard($_rand, $_gift_card_amount, $_increment_id);

                        $_gift_card_id = $_giftCard->load($_rand, 'code')->getData('giftcard_id');

                        $this->insertDataBaseHistory($_gift_card_id, $_customer_id, $_gift_card_amount, 'create');
                    }
                }
            }
    }

    public
    function insertDataBaseGiftCard($code, $giftCardAmount, $incrementId)
    {
        $data = [
            'code' => $code,
            'balance' => $giftCardAmount,
            'created_from' => $incrementId
        ];


        $_giftCard = $this->_giftCardFactory->create();
        $_object_code = $_giftCard->load($code, 'code')->getData('code');
        if (!isset($_object_code)) {
            try {
                $_giftCard->setData($data)->save();
            } catch (\Exception $e) {
                $e->getMessage();
            }
        } else {
            echo 'Khong the them du lieu do bi trung key';
        }
    }

    public
    function insertDataBaseHistory($giftCardId, $customerId, $amount, $action)
    {
        $data = [
            'giftcard_id' => $giftCardId,
            'customer_id' => $customerId,
            'amount' => $amount,
            'action' => $action
        ];
        $_history = $this->_historyFactory->create();
        try {
            $_history->setData($data)->save();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public
    function randomLenght($length)
    {
        $string = 'ABCDEFGHIJKLMLOPQRSTUVXYZ0123456789';
        $size = strlen($string);
        $rand = '';
        for ($i = 0; $i < $length; $i++) {
            $rand .= $string[rand(0, $size - 1)];
        }
        return $rand;
    }
}