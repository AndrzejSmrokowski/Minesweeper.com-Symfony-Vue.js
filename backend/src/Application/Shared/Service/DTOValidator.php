<?php

declare(strict_types=1);

namespace App\Application\Shared\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use App\Application\Shared\Exception\DTOValidatorException;

class DTOValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @throws DTOValidatorException
     */
    public function validate($dto): ConstraintViolationListInterface
    {
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            throw new DTOValidatorException($errors, $this->createErrorMessage($errors));
        }
        return $errors;
    }

    private function createErrorMessage(ConstraintViolationListInterface $errors): string
    {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
        }
        return 'Validation failed with ' . count($errors) . ' error(s): ' . implode(', ', $errorMessages);
    }
}