<?php

declare(strict_types=1);

namespace Dorn\Books\Controller\Index;

use Dorn\Books\Model\BookFactory;
use Dorn\Books\Model\BookRepository;
use Dorn\Books\Request\SaveBookRequest;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Message\ManagerInterface;

class Save implements HttpPostActionInterface
{
    public function __construct(
        private BookFactory $bookFactory,
        private BookRepository $repository,
        private RedirectFactory $redirectFactory,
        private ManagerInterface $message,
        private SaveBookRequest $bookRequest
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if (! $this->bookRequest->validate()) {
            $this->message->addErrorMessage(__($this->bookRequest->getErrorMessage()));

            return $this->redirectFactory->create()->setPath('*/*/create');
        }

        $book = $this->bookFactory->create();

        $book->addData($this->bookRequest->getValidated());

        try {
            $this->repository->save($book);

            $this->message->addSuccessMessage(__('Success! The book was created.'));
        } catch (CouldNotSaveException $e) {
            $this->message->addErrorMessage($e->getMessage());
        }

        return $this->redirectFactory->create()->setPath('books');
    }
}
