<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Observer;

use Dorn\Loyalty\Helper\Data;
use Dorn\Loyalty\Model\CoinsPaymentMethod;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;

class CheckCoinsAmountForPurchaseObserver implements ObserverInterface
{
    public function __construct(
        private CurrentCustomer $currentCustomer,
        private Data $helper
    ) {
    }

    /**
     * @inheritDoc
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(Observer $observer)
    {
        if (! $this->currentCustomer->getCustomerId() || ! $this->helper->isModuleEnabled()) {
            return;
        }

        $paymentMethod = $observer->getOrder()->getPayment()->getMethod();
        if ($paymentMethod === CoinsPaymentMethod::METHOD_CODE) {
            $customerCoinsAmount = $this->currentCustomer
                ->getCustomer()
                ->getCustomAttribute('coins')
                ?->getValue() ?? 0;

            $grandTotal = $observer->getOrder()->getGrandTotal();

            if ($customerCoinsAmount < $grandTotal) {
                throw new LocalizedException(__('You do not have enough coins for this purchase.'));
            }
        }
    }

}