<?php

declare(strict_types=1);

namespace Dorn\RandomProductSidebar\ViewModel;

use Magento\Catalog\Helper\ImageFactory;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class RandomProducts implements ArgumentInterface
{
    public function __construct(
        private CollectionFactory $productCollectionFactory,
        private ImageFactory $imageFactory
    ) {
    }

    public function getProducts(): Collection
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect(['name', 'price', 'thumbnail', 'url_key']);
        $collection->addFieldToFilter('visibility', '4');
        $collection->addAttributeToFilter('type_id', Product\Type::TYPE_SIMPLE);
        $collection->getSelect()->orderRand();
        $collection->setPage(1, 3);

        return $collection;
    }

    public function getProductImageUrl($product): string
    {
        return $this->imageFactory
            ->create()
            ->init($product, 'wishlist_sidebar_block')
            ->getUrl();
    }
}
