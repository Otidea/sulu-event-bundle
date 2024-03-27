<?php

declare(strict_types = 1);

namespace Otd\SuluEventBundle\Exception;

use Sulu\Component\Rest\Exception\TranslationErrorMessageExceptionInterface;

class EventAlreadyExistsException extends \Exception implements TranslationErrorMessageExceptionInterface
{
    /**
     * The id of the Event, which was not found.
     */
    protected string $name;

    /**
     * @param string $name The name of the tag which already exists
     */
    public function __construct(string $name, \Throwable $previous = null)
    {
        $this->name = $name;
        $message = 'The Event with the name "' . $this->name . '" already exists.';
        parent::__construct($message, 0, $previous);
    }

    /**
     * Returns the name of the Event, which already exists.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getMessageTranslationKey(): string
    {
        return 'sulu_tag.tag_already_exists';
    }

    /**
     * @return array<string, mixed>
     */
    public function getMessageTranslationParameters(): array
    {
        return [
            '{name}' => $this->name,
        ];
    }
}
