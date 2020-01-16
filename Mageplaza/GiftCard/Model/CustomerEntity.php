<?php


namespace Mageplaza\GiftCard\Model;


class CustomerEntity extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Mageplaza\GiftCard\Model\ResourceModel\CustomerEntity');
    }
}