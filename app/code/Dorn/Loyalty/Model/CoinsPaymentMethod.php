<?php

namespace Dorn\Loyalty\Model;


use Dorn\Loyalty\Helper\Data;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Payment\Model\Method\AbstractMethod;

class CoinsPaymentMethod extends AbstractMethod
{
    public const METHOD_CODE = 'coins';

    protected $_code = 'coins';

    protected $_canAuthorize = true;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        private Data $helper,
        private CurrentCustomer $currentCustomer,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [],
        DirectoryHelper $directory = null
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $resource,
            $resourceCollection,
            $data,
            $directory
        );
    }

    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null): bool
    {
        if (! $this->helper->isModuleEnabled() || ! $this->currentCustomer->getCustomerId()) {
            return false;
        }

        return parent::isAvailable($quote);
    }
}