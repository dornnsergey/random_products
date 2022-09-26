<?php

declare(strict_types=1);

namespace Dorn\Books\Setup\Patch\Data;

use Dorn\Books\Model\BookFactory;
use Dorn\Books\Model\ResourceModel\Book;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class Books implements DataPatchInterface
{
    public function __construct(
        private BookFactory $bookFactory,
        private Book $resourceBook
    ) {
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        $book = $this->bookFactory->create();

        $book->addData([
            'title'  => 'Angels and Demons',
            'author' => 'Dan Brown',
            'price'  => '666.66',
            'pages'  => '768'
        ]);

        $this->resourceBook->save($book);
    }
}
