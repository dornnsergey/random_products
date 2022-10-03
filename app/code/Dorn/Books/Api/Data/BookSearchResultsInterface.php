<?php

declare(strict_types=1);

namespace Dorn\Books\Api\Data;

interface BookSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return BookInterface[]
     */
    public function getItems(): array;

    /**
     * @param  BookInterface[] $items
     */
    public function setItems(array $items): static;
}
