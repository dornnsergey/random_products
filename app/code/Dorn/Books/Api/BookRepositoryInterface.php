<?php

declare(strict_types=1);

namespace Dorn\Books\Api;

use Dorn\Books\Api\Data\BookInterface;

interface BookRepositoryInterface
{
    public function delete(BookInterface $book): bool;

    public function deleteById(int $bookId): bool;

    public function getById(int $bookId): BookInterface;

    public function save(BookInterface $book): bool;
}
