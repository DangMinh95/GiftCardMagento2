<?php

namespace Mageplaza\GiftCard\Controller\Index;

class Test extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_giftCardFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Mageplaza\GiftCard\Model\GiftCardFactory $GiftCardFactory)
    {
        $this->_giftCardFactory = $GiftCardFactory;
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        $this->deleteDataBase('24');
        die('ket thuc');
        return $this->_pageFactory->create();
    }

    public function insertDataBase($code, $balance = null, $amount_used = null,$create = null)
    {
        $data = [
            'code' => $code,
            'balance' => $balance,
            'amount_used' => $amount_used,
            'created_from'=>$create
        ];
        $giftCard = $this->_giftCardFactory->create();
        $_object_code = $giftCard->load($code, 'code')->getData('code');
        if (!isset($_object_code)) {
            try {
                $giftCard->setData($data)->save();
            } catch (\Exception $e) {
                $e->getMessage();
            }
            echo 'Da them moi du lieu' . '</br>';
        } else {
            echo 'Khong the them du lieu do bi trung key';
//            die(__METHOD__);
        }
    }

    public function deleteDataBase($giftcard_id)
    {
        $giftCard = $this->_giftCardFactory->create();
        $object = $giftCard->load($giftcard_id)->getData('giftcard_id');

        if (isset($object)) {
            try {
                $giftCard->load($giftcard_id)->delete();
            } catch (\Exception $e) {
                $e->getMessage();
            }
            echo 'da xoa bang' . '</br>';
        } else {
            echo 'Khong the xoa do khong ton tai bang can xoa' . "</br>";
//            die(__METHOD__);
        }
    }

    public function updateDataBase($code, $balance, $amount_used, $giftcard_id)
    {
        $data = [
            'giftcard_id' => $giftcard_id,
            'code' => $code,
            'balance' => $balance,
            'amount_used' => $amount_used
        ];
        $giftCard = $this->_giftCardFactory->create();
        $object_id = $giftCard->load($giftcard_id)->getData('giftcard_id');
        $object_code = $giftCard->load($code, 'code')->getData('code');

        if (isset($object_id)) {
            if ($object_code != $code) {
                try {
                    $giftCard->setData($data)->save();
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
            echo 'da cap nhat du lieu' . '</br>';
        } else {
            echo 'khong the cap nhat du lieu' . "</br>";
//            die(__METHOD__);
        }
    }

}