<?php

declare(strict_types=1);

namespace Dorn\Books\Model;

use Dorn\Books\Api\BookRepositoryInterface;
use Dorn\Books\Api\Data\BookInterface;
use Dorn\Books\Api\Data\BookSearchResultsInterface;
use Dorn\Books\Model\ResourceModel\Book as ResourceBook;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class BookRepository implements BookRepositoryInterface
{
    public function __construct(
        private ResourceBook $resource,
        private BookFactory $bookFactory,
        private ResourceBook\CollectionFactory $collectionFactory,
        private CollectionProcessorInterface $collectionProcessor,
        private BookSearchResultsFactory $searchResultsFactory
    ) {
    }

    /**
     * @throws CouldNotDeleteException
     */
    public function delete(BookInterface $book): bool
    {
        try {
            $this->resource->delete($book);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the book: %1', $exception->getMessage())
            );
        }

        return true;
    }

    /**
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $bookId): bool
    {
        return $this->delete($this->getById($bookId));
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getById(int $bookId): BookInterface
    {
        $book = $this->bookFactory->create();
        $this->resource->load($book, $bookId);

        if (! $book->getId()) {
            throw new NoSuchEntityException(__('The book with the "%1" ID doesn\'t exist.', $bookId));
        }

        return $book;
    }

    /**
     * @throws CouldNotSaveException
     */
    public function save(BookInterface $book): bool
    {
        try {
            $this->resource->save($book);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the book: %1', $exception->getMessage())
            );
        }

        return true;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): BookSearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
