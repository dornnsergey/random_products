<?php

declare(strict_types=1);

namespace Dorn\Books\Controller\Index;

use Dorn\Books\Model\BookRepository;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;

class Delete implements HttpGetActionInterface
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
        $bookId = (int) $this->request->getParam('id');

        try {
            $this->repository->deleteById($bookId);

            $this->message->addSuccessMessage(__('Success! The book was deleted.'));
        } catch (CouldNotDeleteException|NoSuchEntityException $e) {
            $this->message->addErrorMessage($e->getMessage());
        }

        return $this->redirectFactory->create()->setPath('books');
    }
}
