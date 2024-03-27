<?php

namespace Otd\SuluEventBundle\Exception;

class EventNotFoundException extends \Exception
{
    /**
     * The id of the Event, which was not found.
     *
     * @var int
     */
    private int $id;

    /**
     * @param int $id The id of the entity, which was not found
     */
    public function __construct($id)
    {
        $this->id = $id;
        $message = 'The Event with the id "' . $id . '" was not found.';
        parent::__construct($message, 0);
    }

    /**
     * Returns the id of the Event, which was not found.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
