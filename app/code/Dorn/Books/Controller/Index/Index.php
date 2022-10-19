<?php

declare(strict_types=1);

namespace Dorn\Books\Controller\Index;

use Dorn\Books\Helper\Data;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class Index implements HttpGetActionInterface
{
    public function __construct(
        private PageFactory $pageFactory,
        private ForwardFactory $forwardFactory,
        private Data $helper
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute(): ResultInterface
    {
        if (! $this->helper->isModuleEnabled()) {
            return $this->forwardFactory->create()->forward('defaultNoRoute');
        }

        return $this->pageFactory->create();
    }
}
