<?php

declare(strict_types=1);

namespace Dorn\Books\Api\Data;

interface BookInterface
{
    public function getTitle(): string;

    public function setTitle(string $title): static;

    public function getAuthor(): string;

    public function setAuthor(string $author): static;

    public function getPrice(): float;

    public function setPrice(float $price): static;

    public function getPages(): int;

    public function setPages(int $pages): static;
}
