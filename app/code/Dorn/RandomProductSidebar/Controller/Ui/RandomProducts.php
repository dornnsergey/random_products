<?php

declare(strict_types=1);

namespace Dorn\RandomProductSidebar\Controller\Ui;

use Dorn\RandomProductSidebar\Service\ProductManager;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class RandomProducts implements HttpGetActionInterface
{
    public function __construct(
        private JsonFactory $resultJsonFactory,
        private ProductManager $productManager
    ) {
    }

    public function execute()
    {
        $products = $this->productManager->getRandomProducts();

        return $this->resultJsonFactory->create()->setData($products);
    }
}
