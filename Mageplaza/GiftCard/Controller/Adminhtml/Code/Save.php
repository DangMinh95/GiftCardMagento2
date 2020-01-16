<?php


namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;

use Mageplaza\GiftCard\Controller\Index\Test;
use Magento\Backend\App\Action;

class Save extends Action
{
    protected $_resultPageFactory;
    protected $_giftCardFactory;
    protected $_test;

    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\App\Action\Context $context,
        \Mageplaza\GiftCard\Model\GiftCardFactory $GiftCardFactory,
        Test $Test
    )
    {
        $this->_giftCardFactory = $GiftCardFactory;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_test = $Test;
        parent::__construct($context);
    }

    public function execute()
    {
        $param = $this->getRequest()->getParams();
        if (isset($param['back'])) {
            /* Khi nhan vao nut save and continue edit trong giao dien edit*/
            if (isset($param['idfield'])) {
                /* Khi nhan vao nut save and continue edit trong giao dien edit*/
                $id = $param['idfield'];
                $this->editDataBase($param['blancefield'], $id);
                return $this->_redirect("giftcard/code/edit/", ['id' => $id]);
            } else {
                /* Khi nhan vao nut save and continue edit trong giao dien create*/
                $_giftcard_model = $this->_giftCardFactory->create();
                $lenght = $this->getRequest()->getParam('codefield');
                $rand = $this->randomLenght($lenght);
                $this->insertDataBase($rand, $param['blancefield']);
                $id = $_giftcard_model->load($rand,'code')->getData('giftcard_id');
                return $this->_redirect("giftcard/code/edit/", ['id' => $id]);
            }
        } else {
            /* Khi nhan vao nut save trong giao dien edit*/
            if (isset($param['idfield'])) {
                /* Giao dien edit*/
                $this->editDataBase($param['blancefield'], $param['idfield']);
                return $this->_redirect('giftcard/code/index');
            } else {
                /* Khi nhan vao nut save trong giao dien create*/
                $lenght = $this->getRequest()->getParam('codelenght');
                $rand = $this->randomLenght($lenght);
                $this->insertDataBase($rand, $param['blancefield']);
                return $this->_redirect('giftcard/code/index');
            }
        }
    }

    protected function insertDataBase($code, $balance)
    {
        $data = [
            'code' => $code,
            'balance' => $balance
        ];

        $giftCard = $this->_giftCardFactory->create();
        $_object_data = $giftCard->load($code, 'code')->getData('code');

        if (!isset($_object_data)) {
            $giftCard->setData($data)->setId(null)->save();
        }
    }

    protected function editDataBase($balance, $id)
    {
        $data = [
            'balance' => $balance
        ];
        $giftCard = $this->_giftCardFactory->create();
        $giftCard->setData($data)->setId($id)->save();
    }

    protected function randomLenght($length)
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