<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Controller\Adminhtml\Customer;

use Dorn\Loyalty\Helper\Data;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;

class Save extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Magento_Backend::system';

    public function __construct(
        Context $context,
        private Data $helper,
        private ManagerInterface $message
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $params = $this->_request->getParam('general');
        $coinsReceived = (float) $params['coins_received'];
        if ($coinsReceived < 0) {
            $this->message->addErrorMessage(__('The coins value can not be less than 0'));
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/');
        }
        $orderId = (int) $params['order_id'];
        $customerId = (int) $params['customer_id'];

        try {
            $this->helper->updateCoinsReceivedValue($orderId, $coinsReceived, $customerId);
            $this->message->addSuccessMessage(__('Value has been successfully updated.'));
        } catch (NoSuchEntityException|LocalizedException $e) {
            $this->message->addErrorMessage($e->getMessage());
        } catch (\Exception) {
            $this->message->addErrorMessage(__('Something went wrong.'));
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)
                                   ->setPath('*/*/edit', ['id' => $orderId]);
    }
}
