<?php

declare(strict_types=1);

namespace Dorn\Books\Api;

use Dorn\Books\Api\Data\BookInterface;
use Dorn\Books\Api\Data\BookSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface BookRepositoryInterface
{
    public function delete(BookInterface $book): bool;

    public function deleteById(int $bookId): bool;

    public function getById(int $bookId): BookInterface;

    public function save(BookInterface $book): bool;

    public function getList(SearchCriteriaInterface $searchCriteria): BookSearchResultsInterface;
}
