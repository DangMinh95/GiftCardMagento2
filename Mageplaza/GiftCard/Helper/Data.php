<?php


namespace Mageplaza\GiftCard\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    const XML_PATH_GIFTCARD = 'giftcard/';

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_GIFTCARD .'general2/'. $code, $storeId);
    }

    public function getValueAllowRedeem($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_GIFTCARD .'general1/'. $code, $storeId);
    }
}