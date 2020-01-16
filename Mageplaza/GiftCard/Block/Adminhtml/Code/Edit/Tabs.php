<?php
namespace Mageplaza\GiftCard\Block\Adminhtml\Code\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('giftcard_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Gift Card Information'));
    }
}
