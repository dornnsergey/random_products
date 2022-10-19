<?php

declare(strict_types=1);

namespace Dorn\Books\Controller\Index;

use Dorn\Books\Model\BookFactory;
use Dorn\Books\Model\BookRepository;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Message\ManagerInterface;

class Save implements HttpPostActionInterface
{
    public function __construct(
        private BookFactory $bookFactory,
        private BookRepository $repository,
        private RedirectFactory $redirectFactory,
        private ManagerInterface $message,
        private RequestInterface $request
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute(): ResultInterface
    {
        $book = $this->bookFactory->create();

        $book->addData($this->request->getParam('book'));

        try {
            $this->repository->save($book);

            $this->message->addSuccessMessage(__('Success! The book was created.'));
        } catch (CouldNotSaveException $e) {
            $this->message->addErrorMessage($e->getMessage());
        } catch (\Exception) {
            $this->message->addErrorMessage(__('Something went wrong.'));
        }

        return $this->redirectFactory->create()->setPath('books');
    }
}
