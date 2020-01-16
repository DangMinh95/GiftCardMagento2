<?php


namespace Mageplaza\GiftCard\Model\ResourceModel\History;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Mageplaza\GiftCard\Model\History', 'Mageplaza\GiftCard\Model\ResourceModel\History');
    }
}