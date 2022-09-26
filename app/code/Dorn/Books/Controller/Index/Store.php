<?php

declare(strict_types=1);

namespace Dorn\Books\Controller\Index;

use Dorn\Books\Model\BookFactory;
use Dorn\Books\Model\BookRepository;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Message\ManagerInterface;

class Store implements HttpPostActionInterface
{
    public function __construct(
        private RequestInterface $request,
        private BookFactory $bookFactory,
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

        $book = $this->bookFactory->create();

        $book->addData($bookData);

        try {
            $this->repository->save($book);

            $this->message->addSuccessMessage(__('Success! The book was created.'));
        } catch (CouldNotSaveException $e) {
            $this->message->addErrorMessage($e->getMessage());
        }

        return $this->redirectFactory->create()->setPath('books');
    }
}
