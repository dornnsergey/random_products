<?php

declare(strict_types=1);

namespace Dorn\Books\Model;

use Dorn\Books\Model\ResourceModel\Book\CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        private RequestInterface $request,
        private BookRepository $bookRepository,
        private RedirectInterface $redirect,
        private Http $response,
        private ManagerInterface $message,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData(): array
    {
        $bookId = (int) $this->request->getParam('id');

        if (! $bookId) {
            return [];
        }

        try {
            $book = $this->bookRepository->getById($bookId);
        } catch (NoSuchEntityException) {
            return [];
        }

        return [
            $bookId => [
                'book' => $book->getData()
            ]
        ];
    }
}
