<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Controller\Index;

use Dorn\Loyalty\Helper\Data;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NotFoundException;

class Index implements HttpGetActionInterface
{
    public function __construct(
        private ResultFactory $resultFactory,
        private CurrentCustomer $currentCustomer,
        private Data $helper
    ) {
    }

    public function execute()
    {
        if (! $this->currentCustomer->getCustomerId() || ! $this->helper->isModuleEnabled()) {
            throw new NotFoundException(__('Page Not Found.'));
        }

        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}