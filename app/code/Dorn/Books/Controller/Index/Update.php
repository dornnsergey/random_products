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
     */
    public function execute()
    {
        $bookData = $this->request->getPostValue()['book'];

        foreach ($bookData as &$item) {
            $item = trim($item);
        }

        $bookId = (int) $this->request->getParam('id');

        try {
            $book = $this->repository->getById($bookId);
        } catch (NoSuchEntityException $e) {
            $this->message->addErrorMessage($e->getMessage());

            return $this->redirectFactory->create()->setPath('*/*/');
        }

        $book->addData($bookData);

        try {
            $this->repository->save($book);

            $this->message->addSuccessMessage(__('Success! The book information was changed.'));
        } catch (CouldNotSaveException $e) {
            $this->message->addErrorMessage($e->getMessage());
        }

        return $this->redirectFactory->create()->setPath('books/index/edit', ['id' => $book->getId()]);
    }
}
