<?php

declare(strict_types=1);

namespace Dorn\Books\ViewModel\Books;

use Dorn\Books\Api\Data\BookInterface;
use Dorn\Books\Model\BookRepository;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;

class Edit implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    public function __construct(
        private RequestInterface $request,
        private BookRepository $repository,
        private RedirectInterface $redirect,
        private Http $response,
        private ManagerInterface $message
    ) {
    }

    public function getBook(): BookInterface
    {
        $id = (int) $this->request->getParam('id');

        try {
            $book = $this->repository->getById($id);
        } catch (NoSuchEntityException $e) {
            $this->message->addErrorMessage($e->getMessage());

            $this->redirect->redirect($this->response, 'books');
        }

        return $book;
    }
}
