<?php

declare(strict_types=1);

namespace Dorn\Books\ViewModel\Books;

use Dorn\Books\Model\Book;
use Dorn\Books\Model\ResourceModel\Book\CollectionFactory;

class Index implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    public function __construct(
        private CollectionFactory $bookCollectionFactory
    ) {
    }

    /**
     * @return Book[]
     */
    public function getBooksCollection(): ?array
    {
        return $this->bookCollectionFactory->create()->getItems();
    }
}
