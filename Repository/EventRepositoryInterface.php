<?php

declare(strict_types = 1);

namespace Otd\SuluEventBundle\Repository;

use Otd\SuluEventBundle\Entity\EventInterface as Event;
use Sulu\Component\Persistence\Repository\RepositoryInterface;

/**
 * Defines the method for the doctrine repository.
 *
 * @extends RepositoryInterface<EventInterface>
 */
interface EventRepositoryInterface extends RepositoryInterface
{
    /**
     * Finds the tag with the given ID.
     */
    public function findEventById(int $id): ?Event;

    /**
     * Finds the tag with the given name.
     */
    public function findEventByName(string $name): ?Event;

    /**
     * Searches for all tags.
     */
    public function findAllEvents(): array;
}
