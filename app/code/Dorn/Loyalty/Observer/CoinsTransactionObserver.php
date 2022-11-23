<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Observer;

use Dorn\Loyalty\Helper\Data;
use Dorn\Loyalty\Model\CoinsPaymentMethod;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;

class CoinsTransactionObserver implements \Magento\Framework\Event\ObserverInterface
{
    public function __construct(
        private ResourceConnection $connection,
        private Data $helper,
        private CustomerRepositoryInterface $customerRepository,
        private ManagerInterface $message
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        if (! $this->helper->isModuleEnabled()) {
            return;
        }

        /** @var \Magento\Sales\Model\Order\Invoice $invoice */
        $invoice = $observer->getInvoice();
        $customerId = $invoice->getCustomerId();

        try {
            $customer = $this->customerRepository->getById($customerId);
        } catch (NoSuchEntityException|LocalizedException) {
            return;
        }

        $paymentMethod = $observer->getPayment()->getMethod();

        $grandTotal = $invoice->getGrandTotal();
        if ($paymentMethod === CoinsPaymentMethod::METHOD_CODE) {
            $column = 'coins_spend';
            $coins = -$grandTotal;
        } else {
            $column = 'coins_received';
            $coins = $this->helper->calculateCoinsBackAmount((float) $grandTotal);
        }

        $currentCustomerCoins = $customer->getCustomAttribute('coins')?->getValue() ?? 0;
        $currentCustomerCoins += $coins;

        $customer->setCustomAttribute('coins', $currentCustomerCoins);
        $orderId = $invoice->getOrderId();

        $connection = $this->connection->getConnection();
        $connection->beginTransaction();
        try {
            $connection->insert('sales_order_coins_transaction', [
                'order_id' => $orderId,
                $column    => abs($coins)
            ]);

            $this->customerRepository->save($customer);

            $connection->commit();
        } catch (\Exception) {
            $connection->rollBack();

            $this->message->addErrorMessage(__('The bonus coins have not been obtained.'));
        }
    }
}