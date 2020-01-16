<?php

namespace Mageplaza\GiftCard\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    protected $_giftCardFactoryFactory;

    public function __construct(\Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactoryFactory)
    {
        $this->_giftCardFactoryFactory = $giftCardFactoryFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $data = [
            'code' => "BAS1111",
            'balance' => 1223,
            'amount_used' => 1111,
        ];
        $giftCard = $this->_giftCardFactoryFactory->create();
        $giftCard->addData($data)->save();
    }
}