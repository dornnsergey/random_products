<?php

declare(strict_types=1);

namespace Dorn\RandomProductSidebar\ViewModel;

use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Escaper;
use Magento\Framework\Pricing\Helper\Data;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class RandomProducts implements ArgumentInterface
{
    public function __construct(
        private CollectionFactory $productCollectionFactory,
        private Escaper $escaper,
        private Data $priceHelper,
        private Image $imageHelper,
        private SerializerInterface $serializer
    ) {
    }

    public function getProductsJson(): bool|string
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect(['name', 'price', 'thumbnail', 'url_key']);
        $collection->addAttributeToFilter('visibility', Product\Visibility::VISIBILITY_BOTH);
        $collection->addAttributeToFilter('type_id', Product\Type::TYPE_SIMPLE);
        $collection->getSelect()->orderRand()->limit(3);
        $products = $collection->getItems();

        $result = [];
        /** @var Product $product */
        foreach ($products as $product) {
            $result[] = [
                'name'     => $this->escaper->escapeHtml($product->getName()),
                'price'    => $this->priceHelper->currency($product->getPrice()),
                'imageUrl' => $this->imageHelper->init($product, 'wishlist_sidebar_block')->getUrl(),
                'url'      => $this->escaper->escapeUrl($product->getProductUrl())
            ];
        }

        return $this->serializer->serialize($result);
    }
}
