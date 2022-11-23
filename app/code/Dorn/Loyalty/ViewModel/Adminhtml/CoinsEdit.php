<?php

declare(strict_types=1);

namespace Dorn\Loyalty\ViewModel\Adminhtml;

use Dorn\Loyalty\Helper\Data;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class CoinsEdit implements ArgumentInterface
{
    public function __construct(
        private Data $helper,
        private RequestInterface $request
    ) {
    }

    public function getData(): array|bool
    {
        return $this->helper->getCoinsTransaction((int) $this->request->getParam('id'));
    }
}
