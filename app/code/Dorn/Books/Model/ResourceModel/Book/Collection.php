<?php

declare(strict_types=1);

namespace Dorn\Books\Model\ResourceModel\Book;

use Dorn\Books\Model\Book;
use Dorn\Books\Model\ResourceModel\Book as ResourceBook;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(Book::class, ResourceBook::class);
    }
}
