<?php

declare(strict_types=1);

namespace Dorn\Books\ViewModel\Books;

use Dorn\Books\Model\BookRepository;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;

class Edit implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    public function __construct(
        private UrlInterface $urlBuilder,
        private RequestInterface $request,
        private BookRepository $repository,
        private RedirectInterface $redirect,
        private Http $response,
        private ManagerInterface $message
    ) {
    }

    public function getUpdateBookUrl(int $id): string
    {
        return $this->urlBuilder->getUrl('books/index/update', ['id' => $id]);
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getBook()
    {
        $id = (int)$this->request->getParam('id');

        try {
            $book =  $this->repository->getById($id);
        } catch (NoSuchEntityException $e) {
            $this->message->addErrorMessage(__('Error! This book doesn\'t exist.'));

            $this->redirect->redirect($this->response, 'books');
        }

        return $book;
    }
}
