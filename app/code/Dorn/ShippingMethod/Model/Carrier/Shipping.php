<?php

declare(strict_types=1);

namespace Dorn\ShippingMethod\Model\Carrier;

use Magento\Checkout\Helper\Data as CheckoutHelper;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\ResultFactory;

class Shipping extends AbstractCarrier implements CarrierInterface
{
    protected $_code = 'dornshipping';

    public const UA_COUNTRY_ID = 'UA';

    protected array $countriesRate = [
        self::CANADA_COUNTRY_ID => 7,
        self::USA_COUNTRY_ID    => 3,
        self::UA_COUNTRY_ID     => 2,
    ];

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        private CheckoutHelper $checkoutHelper,
        private ResultFactory $rateResultFactory,
        private MethodFactory $rateMethodFactory,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * @inheritDoc
     */
    public function collectRates(RateRequest $request)
    {
        if (! $this->getConfigFlag('active')) {
            return false;
        }

        $result = $this->rateResultFactory->create();
        $method = $this->rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));

        $shippingCost = $this->getShippingCost($request);

        $method->setPrice($shippingCost);
        $method->setCost($shippingCost);

        $result->append($method);

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getAllowedMethods(): array
    {
        return [$this->_code => $this->getConfigData('name')];
    }

    private function getShippingCost(RateRequest $request): int|float
    {
        $quote = $this->checkoutHelper->getQuote();

        $baseCartTotal = $quote->getBaseSubtotal();
        $uniqueProductsInCard = $quote->getItemsCount();
        $totalProductsInCard = $request->getPackageQty();
        $shippingFee = $this->getConfigData('shipping_fee');
        $countryRate = $this->getCountryRate($request->getDestCountryId());

        return ($baseCartTotal * $uniqueProductsInCard * $shippingFee) / $totalProductsInCard * $countryRate;
    }

    private function getCountryRate(string $country): int
    {
        return \array_key_exists($country, $this->countriesRate)
            ? $this->countriesRate[$country]
            : 1;
    }
}