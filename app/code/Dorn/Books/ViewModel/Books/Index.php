<?php

declare(strict_types=1);

namespace Dorn\Books\ViewModel\Books;

use Dorn\Books\Model\Book;
use Dorn\Books\Model\ResourceModel\Book\CollectionFactory;
use Magento\Framework\UrlInterface;

class Index implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    public function __construct(
        private CollectionFactory $bookCollectionFactory,
        private UrlInterface $urlBuilder
    ) {
    }

    /**
     *  @return Book[]
     */
    public function getBooksCollection(): ?array
    {
        return $this->bookCollectionFactory->create()->getItems();
    }

    public function getCreateBookUrl(): string
    {
        return $this->urlBuilder->getUrl('books/index/create');
    }

    public function getEditBookUrl(int $id): string
    {
        return $this->urlBuilder->getUrl('books/index/edit', ['id' => $id]);
    }

    public function getDeleteBookUrl(int $id): string
    {
        return $this->urlBuilder->getUrl('books/index/delete', ['id' => $id]);
    }
}
