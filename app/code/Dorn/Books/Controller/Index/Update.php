<?php

declare(strict_types=1);

namespace Dorn\Books\Controller\Index;

use Dorn\Books\Model\BookRepository;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;

class Update implements HttpPostActionInterface
{
    public function __construct(
        private RequestInterface $request,
        private BookRepository $repository,
        private RedirectFactory $redirectFactory,
        private ManagerInterface $message
    ) {
    }

    /**
     * @inheritDoc
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $bookData = $this->request->getPostValue();

        foreach ($bookData as &$item) {
            $item = trim($item);
        }

        $bookId = (int)$this->request->getParam('id');

        $book = $this->repository->getById($bookId);

        $book->setTitle($bookData['title'])
             ->setAuthor($bookData['author'])
             ->setPrice((float)$bookData['price'])
             ->setPages((int)$bookData['pages']);

        $this->repository->save($book);

        $this->message->addSuccessMessage(__('Success! The book information was changed.'));

        return $this->redirectFactory->create()->setPath('books/index/edit', ['id' => $book->getId()]);
    }
}
