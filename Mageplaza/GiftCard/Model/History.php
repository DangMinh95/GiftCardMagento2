<?php
namespace Mageplaza\GiftCard\Model;

class History extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Mageplaza\GiftCard\Model\ResourceModel\History');
    }
}