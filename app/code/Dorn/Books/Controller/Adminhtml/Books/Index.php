<?php

declare(strict_types=1);

namespace Dorn\Books\Controller\Adminhtml\Books;

use Magento\Framework\View\Result\PageFactory;

class Index implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    public function __construct(
        private PageFactory $pageFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        return $this->pageFactory->create();
    }
}
