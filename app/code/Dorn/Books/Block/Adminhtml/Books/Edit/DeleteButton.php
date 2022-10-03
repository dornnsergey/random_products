<?php

declare(strict_types=1);

namespace Dorn\Books\Block\Adminhtml\Books\Edit;

use Dorn\Books\Model\BookRepository;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton implements ButtonProviderInterface
{
    public function __construct(
        private BookRepository $bookRepository,
        private RequestInterface $request,
        private UrlInterface $urlBuilder
    ) {
    }

    public function getButtonData(): array
    {
        $data = [];

        if ($this->checkIfBookExists()) {
            $data = [
                'label'      => __('Delete'),
                'class'      => 'primary',
                'on_click'   => 'deleteConfirm(\'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $this->getDeleteUrl() . '\', {"data": {}})'
            ];
        }

        return $data;
    }

    private function getDeleteUrl(): string
    {
        return $this->urlBuilder->getUrl('*/*/delete', ['id' => $this->request->getParam('id')]);
    }

    private function checkIfBookExists(): bool
    {
        $id = (int) $this->request->getParam('id');

        try {
            $this->bookRepository->getById($id);
        } catch (NoSuchEntityException) {
            return false;
        }

        return true;
    }
}
