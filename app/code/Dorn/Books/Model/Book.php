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

    public function getId(): mixed
    {
        return $this->getData(self::ID);
    }

    public function setId($id): static
    {
        return $this->setData(self::ID, $id);
    }

    public function getTitle(): string
    {
        return $this->getData(self::TITLE);
    }

    public function setTitle(string $title): static
    {
        return $this->setData(self::TITLE, $title);
    }

    public function getAuthor(): string
    {
        return $this->getData(self::AUTHOR);
    }

    public function setAuthor(string $author): static
    {
        return $this->setData(self::AUTHOR, $author);
    }

    public function getPrice(): float
    {
        return (float)$this->getData(self::PRICE);
    }

    public function setPrice(float $price): static
    {
        return $this->setData(self::PRICE, $price);
    }

    public function getPages(): int
    {
        return (int)$this->getData(self::PAGES);
    }

    public function setPages(int $pages): static
    {
        return $this->setData(self::PAGES, $pages);
    }
}
