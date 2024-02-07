<?php

declare(strict_types=1);

namespace App\Application\Shared\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Exception thrown when DTO validation fails.
 *
 * Holds detailed information about validation errors that can be used to inform the user
 * or for further processing within the application.
 */
class DTOValidatorException extends Exception
{
    /**
     * @var ConstraintViolationListInterface List of validation errors.
     */
    private ConstraintViolationListInterface $errors;

    /**
     * Constructs a DTOValidatorException.
     *
     * @param ConstraintViolationListInterface $errors A list of validation errors.
     * @param string $message (optional) The exception message.
     * @param int $code (optional) The error code.
     * @param \Throwable|null $previous (optional) The previous throwable used for exception chaining.
     */
    public function __construct(ConstraintViolationListInterface $errors, string $message = "DTO validation failed", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    /**
     * Returns the list of validation errors.
     *
     * @return ConstraintViolationListInterface A list of validation errors.
     */
    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->errors;
    }
}