<?php

declare(strict_types = 1);

namespace Otd\SuluEventBundle\Controller\Admin\Exception;

use Sulu\Component\Rest\Exception\RestException;

class ConstraintViolationException extends RestException
{
    /**
     * Error code for non unique tag name.
     *
     * @var int
     */
    public const EXCEPTION_CODE_NON_UNIQUE_NAME = 1101;

    /**
     * The field of the tag which is not unique.
     */
    protected string $field;

    /**
     * @param string $message The error message
     * @param string $field The field which is not
     * @param int $code
     */
    public function __construct(string $message, $field, $code = 0)
    {
        $this->field = $field;
        parent::__construct($message, $code);
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'field' => $this->field,
        ];
    }
}
