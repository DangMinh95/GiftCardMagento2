<?php


namespace Mageplaza\GiftCard\Block;

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Stdlib\BooleanUtils;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class MyGiftCard extends \Magento\Framework\View\Element\Template
{
    protected $_currentCustomer;
    protected $collectionFactory;
    protected $_giftFactory;
//    protected $_scopeConfig;
    protected $localeCurrency;
    protected $timezone;
    private $booleanUtils;
    protected $storeManager;
    protected $_priceCurrency;
    protected $_helper_data;

    public function __construct(
        \Mageplaza\GiftCard\Helper\Data $helperData,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Mageplaza\GiftCard\Model\ResourceModel\History\CollectionFactory $collectionFactory,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftcardFactory,
//        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Locale\CurrencyInterface $localeCurrency,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        TimezoneInterface $timezone,
        BooleanUtils $booleanUtils,
        PriceCurrencyInterface $priceCurrencyInterface,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_priceCurrency = $priceCurrencyInterface;
//        $this->_scopeConfig = $scopeConfig;
        $this->_giftFactory = $giftcardFactory;
        $this->_currentCustomer = $currentCustomer;
        $this->collectionFactory = $collectionFactory;
        $this->localeCurrency = $localeCurrency;
        $this->storeManager = $storeManager;
        $this->timezone = $timezone;
        $this->booleanUtils = $booleanUtils;
        $this->_helper_data = $helperData;
    }

    public function getItems()
    {
        $gift = $this->_giftFactory->create();
        $currentCustomerId = $this->_currentCustomer->getCustomerId();
        $collection = $this->collectionFactory->create()->addFieldToFilter('customer_id', $currentCustomerId);
        $data = $collection->getData();

        foreach ($data as $key => $value) {
            $code = $gift->load($value['giftcard_id'])->getCode();
            $data[$key]['giftcard_code'] = $code;
            $value['action_time'] = $this->formatDateTime($value['action_time']);
        }
        return $data;
    }

    public function getBalance()
    {
        $balance = 0;
        $collection = $this->collectionFactory->create();
        $currentCustomerId = $this->_currentCustomer->getCustomerId();
        $data = $collection->addFieldToFilter('customer_id', $currentCustomerId)
            ->addFieldToFilter('action','redeem')->getData();

        foreach ($data as $item) {
            $balance += $item['amount'];
        }
        return $this->formatPrice($balance);
    }

    public function getValueRedeem()
    {
//        $value = $this->_scopeConfig->getValue('giftcard/general1/allow_redeem', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
//        $valueRedeem = $value['giftcard']['general1']['allow_redeem'];
        $value = $this->_helper_data->getValueAllowRedeem('allow_redeem');
        return $value;
    }

    public function getRedeemUrl()
    {
        return $this->getUrl('giftcardname/customer/redeem');
    }

    public function formatPrice($price)
    {
        $store = $this->storeManager->getStore();
        $sym = $store->getBaseCurrencyCode();
        $currency = $this->localeCurrency->getCurrency($sym);
        $price = $currency->toCurrency(sprintf("%f", $price));
        return $price;
//        return $this->_priceCurrency->convertAndFormat($price);
    }

    public function formatDateTime($dateTime)
    {
        $date = $this->timezone->date($dateTime);
        $timezone = isset($this->getConfiguration()['timezone'])
            ? $this->booleanUtils->convert($this->getConfiguration()['timezone'])
            : true;
        if (!$timezone) {
            $date = $this->timezone->date($dateTime);
        }
        return $date->format('d/m/Y');
    }
}