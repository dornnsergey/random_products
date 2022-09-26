<?php

declare(strict_types=1);

namespace Dorn\Books\Model;

use Dorn\Books\Model\ResourceModel\Book\CollectionFactory;
use Magento\Framework\App\RequestInterface;
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
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData(): array
    {
        $bookId = (int) $this->request->getParam('id') ?? null;

        if (! $bookId) {
            return [];
        }

        return [
            $bookId => [
                'book' => $this->bookRepository->getById($bookId)->getData()
            ]
        ];
    }
}
