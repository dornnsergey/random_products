<?php

declare(strict_types=1);

namespace Dorn\Books\Controller\Adminhtml\Books;

use Dorn\Books\Model\BookFactory;
use Dorn\Books\Model\BookRepository;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;

class Save implements HttpPostActionInterface
{
    public function __construct(
        private RequestInterface $request,
        private BookRepository $bookRepository,
        private BookFactory $bookFactory,
        private RedirectFactory $redirectFactory,
        private ManagerInterface $message
    ) {
    }

    public function execute(): ResultInterface
    {
        $bookId = $this->request->getParam('id');

        if ($bookId) {
            try {
                $book = $this->bookRepository->getById($bookId);
            } catch (NoSuchEntityException $e) {
                $this->message->addErrorMessage($e->getMessage());

                return $this->redirectFactory->create()->setPath('*/*/');
            } catch (\Exception) {
                $this->message->addErrorMessage(__('Something went wrong.'));

                return $this->redirectFactory->create()->setPath('*/*/');
            }
        } else {
            $book = $this->bookFactory->create();
        }

        $book = $book->addData($this->request->getParam('book'));

        try {
            $this->bookRepository->save($book);

            $this->message->addSuccessMessage(__('Success! The book was created.'));
        } catch (CouldNotSaveException $e) {
            $this->message->addErrorMessage($e->getMessage());
        } catch (\Exception) {
            $this->message->addErrorMessage(__('Something went wrong.'));
        }

        return $this->redirectFactory->create()->setPath('dorn/books/index');
    }
}
