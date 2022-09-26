<?php

declare(strict_types=1);

namespace Dorn\Books\ViewModel\Books;

use Magento\Framework\UrlInterface;

class Create implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    public function __construct(
        private UrlInterface $urlBuilder
    ) {
    }

    public function getStoreBookUrl(): string
    {
        return $this->urlBuilder->getUrl('books/index/store');
    }
}
