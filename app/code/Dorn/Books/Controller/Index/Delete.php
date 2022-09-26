<?php

declare(strict_types=1);

namespace Dorn\Books\Controller\Index;

use Dorn\Books\Model\BookRepository;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;

class Delete implements HttpPostActionInterface
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
        $bookId = $this->request->getParam('id');

        $this->repository->deleteById((int)$bookId);

        $this->message->addSuccessMessage(__('Success! The book was deleted.'));

        return $this->redirectFactory->create()->setPath('books');
    }
}
