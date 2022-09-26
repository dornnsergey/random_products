<?php

declare(strict_types=1);

namespace Dorn\Books\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Data
{
    public function __construct(
        private ScopeConfigInterface $config
    ) {
    }

    public function isModuleEnabled()
    {
        return $this->config->getValue('books/general/enable');
    }
}
