<?php

declare(strict_types=1);

namespace Dorn\Books\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Book extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('dorn_books', 'id');
    }
}
