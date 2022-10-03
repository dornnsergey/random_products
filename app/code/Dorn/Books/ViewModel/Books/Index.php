<?php

declare(strict_types=1);

namespace Dorn\Books\ViewModel\Books;

use Dorn\Books\Api\Data\BookInterface;
use Dorn\Books\Model\ResourceModel\Book\CollectionFactory;

class Index implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    public function __construct(
        private CollectionFactory $bookCollectionFactory
    ) {
    }

    /**
     * @return BookInterface[]
     */
    public function getBooksCollection(): ?array
    {
        return $this->bookCollectionFactory->create()->getItems();
    }
}
