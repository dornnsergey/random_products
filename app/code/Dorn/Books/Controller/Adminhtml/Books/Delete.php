<?php

declare(strict_types=1);

namespace Dorn\Books\Controller\Adminhtml\Books;

use Dorn\Books\Model\BookRepository;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;

class Delete implements HttpPostActionInterface
{
    public function __construct(
        private RequestInterface $request,
        private BookRepository $bookRepository,
        private RedirectFactory $redirectFactory,
        private ManagerInterface $message
    ) {
    }

    public function execute(): ResultInterface
    {
        $bookId = (int) $this->request->getParam('id');

        try {
            $this->bookRepository->deleteById($bookId);

            $this->message->addSuccessMessage(__('Success! The book was deleted.'));
        } catch (CouldNotDeleteException|NoSuchEntityException $e) {
            $this->message->addErrorMessage($e->getMessage());
        } catch (\Exception) {
            $this->message->addErrorMessage(__('Something went wrong.'));
        }

        return $this->redirectFactory->create()->setPath('dorn/books/index');
    }
}
