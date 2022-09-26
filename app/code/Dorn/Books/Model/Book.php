<?php

declare(strict_types=1);

namespace Dorn\Books\Model;

use Dorn\Books\Api\Data\BookInterface;

class Book extends \Magento\Framework\Model\AbstractModel implements BookInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel\Book::class);
    }

    public function getTitle(): string
    {
        return $this->getData('title');
    }

    public function setTitle(string $title): static
    {
        return $this->setData('title', $title);
    }

    public function getAuthor(): string
    {
        return $this->getData('author');
    }

    public function setAuthor(string $author): static
    {
        return $this->setData('author', $author);
    }

    public function getPrice(): float
    {
        return (float)$this->getData('price');
    }

    public function setPrice(float $price): static
    {
        return $this->setData('price', $price);
    }

    public function getPages(): int
    {
        return (int)$this->getData('pages');
    }

    public function setPages(int $pages): static
    {
        return $this->setData('pages', $pages);
    }
}
