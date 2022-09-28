<?php

declare(strict_types=1);

namespace Dorn\Books\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Data
{
    public const XML_PATH_IS_ENABLED = 'books/general/enable';

    public function __construct(
        private ScopeConfigInterface $config
    ) {
    }

    public function isModuleEnabled()
    {
        return $this->config->getValue(self::XML_PATH_IS_ENABLED);
    }
}
