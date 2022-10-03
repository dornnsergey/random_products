<?php

declare(strict_types=1);

namespace Dorn\Books\ViewModel\Books;

use Dorn\Books\Api\Data\BookInterface;
use Dorn\Books\Model\BookRepository;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Edit implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    public function __construct(
        private RequestInterface $request,
        private BookRepository $repository,
    ) {
    }

    public function getBook(): BookInterface
    {
        $id = (int) $this->request->getParam('id');

        try {
            $book = $this->repository->getById($id);
        } catch (NoSuchEntityException) {
        }

        return $book;
    }
}
