<?php

declare(strict_types=1);

namespace Dorn\RandomProductSidebar\ViewModel;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class RandomProducts implements ArgumentInterface
{
    public function __construct(
        private CollectionFactory $productCollectionFactory,
    ) {
    }

    public function getProducts(): ProductCollection
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect(['name', 'price', 'thumbnail', 'url_key']);
        $collection->addFieldToFilter('visibility', Product\Visibility::VISIBILITY_BOTH);
        $collection->addAttributeToFilter('type_id', Product\Type::TYPE_SIMPLE);
        $collection->getSelect()->orderRand();
        $collection->setPage(1, 3);

        return $collection;
    }
}
