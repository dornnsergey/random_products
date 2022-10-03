<?php

declare(strict_types=1);

namespace Dorn\Books\Request;

use Magento\Framework\App\RequestInterface;

class SaveBookRequest
{
    private ?string $error = null;

    private array $validated = [];

    public function __construct(
        private RequestInterface $request
    ) {
    }

    public function validate(): bool
    {
        $inputFormData = $this->request->getParam('book');

        foreach ($inputFormData as $field => &$value) {
            $value = trim($value);

            if (empty($value)) {
                $this->error = ucfirst($field) . ' field is required.';
                return false;
            }
        }

        if (mb_strlen($inputFormData['title']) > 255) {
            $this->error = 'The Book Title may not be greater than 255 characters.';
            return false;
        }

        $this->validated['title'] = $inputFormData['title'];

       if (mb_strlen($inputFormData['author']) > 255) {
            $this->error = 'The Book Author may not be greater than 255 characters.';
            return false;
        }

        $this->validated['author'] = $inputFormData['author'];

        if (! filter_var($inputFormData['pages'], FILTER_VALIDATE_INT)) {
            $this->error = 'The Total Pages value must be an integer number.';
            return false;
        }

        $this->validated['pages'] = $inputFormData['pages'];

        if (! is_numeric($inputFormData['price'])) {
            $this->error = 'The Book Price value must be a number.';
            return false;
        }

        if ((int) $inputFormData['price'] > 999999.99) {
            $this->error = 'The Book Price value may not be greater than 999999.99.';
            return false;
        }

        $this->validated['price'] = $inputFormData['price'];

        return true;
    }

    public function getErrorMessage(): ?string
    {
        return $this->error;
    }

    public function getValidated(): array
    {
        return $this->validated;
    }
}
