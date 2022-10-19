<?php

declare(strict_types=1);

namespace Dorn\Books\Model;

use Dorn\Books\Api\Data\BookInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Phrase;

class Book extends AbstractModel implements BookInterface
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
        return (float) $this->getData(self::PRICE);
    }

    public function setPrice(float $price): static
    {
        return $this->setData(self::PRICE, $price);
    }

    public function getPages(): int
    {
        return (int) $this->getData(self::PAGES);
    }

    public function setPages(int $pages): static
    {
        return $this->setData(self::PAGES, $pages);
    }

    public function getCreatedAt(): string
    {
        return $this->getData(self::CREATED_AT);
    }

    public function setCreatedAt(string $createdAt): static
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    public function getUpdatedAt(): string
    {
        return $this->getData(self::UPDATED_AT);
    }

    public function setUpdatedAt(string $updatedAt): static
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * @throws \Zend_Validate_Exception
     * @throws LocalizedException
     */
    public function validateBeforeSave()
    {
        if (! \Zend_Validate::is(trim($this->getTitle()), 'NotEmpty')) {
            throw new LocalizedException(new Phrase('The Book Title field is required.'));
        }

        if (! \Zend_Validate::is(trim($this->getTitle()), 'StringLength', [3, 255])) {
            throw new LocalizedException(
                new Phrase('The Book Title may not be less than 3 or greater than 255 characters.')
            );
        }

        if (! \Zend_Validate::is(trim($this->getAuthor()), 'NotEmpty')) {
            throw new LocalizedException(new Phrase('The Book Author field is required.'));
        }

        if (! \Zend_Validate::is(trim($this->getAuthor()), 'StringLength', [3, 255])) {
            throw new LocalizedException(
                new Phrase('The Book Author may not be less than 3 or greater than 255 characters.')
            );
        }

        if (! filter_var($this->getPages(), FILTER_VALIDATE_INT)) {
            throw new LocalizedException(
                new Phrase('The Total Pages value must be an integer number.')
            );
        }

        if (! is_numeric($this->getPrice())) {
            throw new LocalizedException(
                new Phrase('The Book Price value must be a number.')
            );
        }

        if ($this->getPrice() > 999999.99) {
            throw new LocalizedException(
                new Phrase('The Book Price value may not be greater than 999999.99.')
            );
        }
    }
}
