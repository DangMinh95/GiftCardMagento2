<?php


namespace Mageplaza\GiftCard\Block\Adminhtml\Code\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Code extends Generic implements TabInterface
{
    protected $_giftCardFactory;
    protected $_registry;
    protected $_redirect;
    protected $_response;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Mageplaza\GiftCard\Model\GiftCardFactory $GiftCardFactory,
        \Magento\Framework\App\Response\Http $reaponse,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        array $data = []
    )
    {
        $this->_registry = $registry;
        $this->_giftCardFactory = $GiftCardFactory;
        $this->_redirect = $redirect;
        $this->_response = $reaponse;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $_url_param = $this->getRequest()->getParams();
        $_model_gift_card_id = $this->_registry->registry('giftcard');
        $form = $this->_formFactory->create();
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Gift Card Information')]
        );
        $_object_id = $_model_gift_card_id->getData('giftcard_id');
        /* kiem tra dieu kien de nhay vao tung loai form */
        if (isset($_url_param['id']) && isset($_object_id)) {
            $fieldset->addField(
                'idfield',
                'hidden',
                [
                    'name' => 'idfield',
                    'value' => $_model_gift_card_id->getData('giftcard_id'),
                ]
            );
            $fieldset->addField(
                'codelenght',
                'text',
                [
                    'name' => 'codefield',
                    'label' => __('Code'),
                    'value' => $_model_gift_card_id->getData('code'),
                    'readonly' => true,
                ]
            );

            $fieldset->addField(
                'balance',
                'text',
                [
                    'name' => 'blancefield',
                    'label' => __('Balance'),
                    'value' => $_model_gift_card_id->getData('balance'),
                ]
            );

            $fieldset->addField(
                'createfrom:',
                'text',
                [
                    'name' => 'createfrom',
                    'label' => __('Create From:'),
                    'value' => $_model_gift_card_id->getData('created_from'),
                    'readonly' => true,
                ]
            );

        } else if (isset($_url_param['id']) && !isset($_object_id)) {
//            echo "<pre>";
//            echo 'Da check khong co id tren url';
//            echo "</pre>";
            $this->_redirect->redirect($this->_response, 'giftcard/code/index');
        } else {
            $fieldset->addField(
                'codelenght',
                'text',
                [
                    'name' => 'codefield',
                    'label' => __('Code'),
                    'value' => ''
                ]
            );

            $fieldset->addField(
                'balance',
                'text',
                [
                    'name' => 'blancefield',
                    'label' => __('Balance'),
                    'value' => '',
                    'required' => true
                ]
            );
        }
    }

    public function getTabLabel()
    {
        return __('Gift Card Table');
    }

    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

}