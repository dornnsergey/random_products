<?php

declare(strict_types=1);

namespace Dorn\Books\Controller\Adminhtml\Books;

use Dorn\Books\Model\BookFactory;
use Dorn\Books\Model\BookRepository;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;

class Save implements HttpPostActionInterface
{
    public function __construct(
        private RequestInterface $request,
        private BookRepository $bookRepository,
        private BookFactory $bookFactory,
        private RedirectFactory $redirectFactory
    ) {
    }

    public function execute()
    {
        $bookId = $this->request->getParam('id') ?? null;

        if ($bookId) {
            $book = $this->bookRepository->getById($bookId);
        } else {
            $book = $this->bookFactory->create();
        }

        $bookData = $this->request->getPostValue()['book'];

        $book = $book->addData($bookData);

        $this->bookRepository->save($book);

        return $this->redirectFactory->create()->setPath('dorn/books/index');
    }
}
