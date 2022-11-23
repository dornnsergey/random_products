<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Helper;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Message\ManagerInterface;

class Data extends AbstractHelper
{
    private const XML_PATH_MODULE_ENABLE = 'loyalty/general/enable';
    private const XML_PATH_ENABLE_PDP_MESSAGE = 'loyalty/general/enable_pdp_message';
    private const XML_PATH_COINSBACK_PERCENT = 'loyalty/general/coins_back_percent';
    public const DB_COINS_TRANSACTION = 'sales_order_coins_transaction';

    private array|bool $loadedTransactions = [];

    public function __construct(
        Context $context,
        private ResourceConnection $resourceConnection,
        private CustomerRepositoryInterface $customerRepository,
        private ManagerInterface $message
    ) {
        parent::__construct($context);
    }

    public function isModuleEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_MODULE_ENABLE);
    }

    public function shouldDisplayCoinsBackMessage(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLE_PDP_MESSAGE)
            && $this->scopeConfig->isSetFlag(self::XML_PATH_MODULE_ENABLE);
    }

    public function calculateCoinsBackAmount(float $amount): float
    {
        $coinsBackValue = $this->scopeConfig->getValue(self::XML_PATH_COINSBACK_PERCENT) / 100;

        return $amount * $coinsBackValue;
    }

    public function getCoinsTransaction(int $orderId = null, int $customerId = null): array|bool
    {
        if ($this->loadedTransactions === []) {
            $connection = $this->resourceConnection->getConnection();
            $select = $connection->select()
                                 ->from(self::DB_COINS_TRANSACTION)
                                 ->join(
                                     'sales_order',
                                     'order_id=entity_id',
                                     [
                                         'customer_id',
                                         'increment_id',
                                         'base_subtotal',
                                         'created_at'
                                     ]
                                 );

            $bind = '';
            $where = '';
            // this part only for admin coins transaction edit form part
            if ($orderId !== null) {
                $bind = [':order_id' => $orderId];
                $where = 'order_id=:order_id';
                $method = 'fetchRow';
            }
            // this part only for frontend coins transaction list
            if ($customerId !== null) {
                $bind = [':customer_id' => $customerId];
                $where = 'customer_id=:customer_id';
                $method = 'fetchAssoc';
            }
            $select->where($where);

            $this->loadedTransactions = $connection->$method($select, $bind);
        }

        return $this->loadedTransactions;
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function updateCoinsReceivedValue(int $orderId, float $newCoinsReceived, int $customerId)
    {
        $connection = $this->resourceConnection->getConnection();
        $customer = $this->customerRepository->getById($customerId);
        $currentCustomerCoinsAmount = $customer->getCustomAttribute('coins')?->getValue() ?? 0;

        $oldCoinsReceived = $this->getCoinsTransaction($orderId)['coins_received'];
        $changedCoinsValue = $newCoinsReceived - $oldCoinsReceived;

        $customer->setCustomAttribute('coins', $currentCustomerCoinsAmount + $changedCoinsValue);

        $bind = ['coins_received' => $newCoinsReceived, 'added_by_admin' => true];
        $where = ['order_id = ?' => $orderId];

        $connection->beginTransaction();
        try {
            $connection->update(self::DB_COINS_TRANSACTION, $bind, $where);
            $this->customerRepository->save($customer);

            $connection->commit();
        } catch (\Exception) {
            $connection->rollBack();
            $this->message->addErrorMessage(__('Coins Value has not been changed.'));
        }
    }
}