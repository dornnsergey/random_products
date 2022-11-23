<?php

declare(strict_types=1);

namespace Dorn\Loyalty\ViewModel;

use Dorn\Loyalty\Helper\Data;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class CoinsList implements ArgumentInterface
{
    public function __construct(
        private Data $helperData,
        private CurrentCustomer $currentCustomer
    ) {
    }

    public function getTotalCustomerCoins()
    {
        return $this->currentCustomer->getCustomer()
                                     ->getCustomAttribute('coins')
                                     ?->getValue() ?? 0;
    }

    public function getData(): array|bool
    {
        return $this->helperData->getCoinsTransaction(customerId: (int) $this->currentCustomer->getCustomerId());
    }
}