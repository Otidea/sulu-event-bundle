<?php

declare(strict_types = 1);

namespace Otd\SuluEventBundle\Entity\Factory;

use Otd\SuluEventBundle\Entity\Event;
use Otd\SuluEventBundle\Repository\EventRepository;
use Sulu\Component\Security\Authentication\UserRepositoryInterface;

class EventFactory extends AbstractFactory
{
    public function __construct(
        private readonly EventRepository $eventRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function generateEventFromRequest(Event $event, array $data, string $locale): Event
    {
        if ($this->getProperty($data, 'name')) {
            $event->setId($this->getProperty($data, 'name'));
        }

        if ($this->getProperty($data, 'startDate')) {
            $event->setStartDate($this->getProperty($data, 'startDate'));
        }

        if ($this->getProperty($data, 'endDate')) {
            $event->setEndDate($this->getProperty($data, 'endDate'));
        }

        if (!$event->getId()) {
            $event->setCreated(new \DateTime());
        }

        if ($locale) {
            $event->setDefaultLocale($locale);
        }

        if ($authored = $this->getProperty($data, 'authored')) {
            $event->setCreated(new \DateTime($authored));
        }

        if ($author = $this->getProperty($data, 'author')) {
            $event->setCreator($this->userRepository->find($author));
        }

        return $event;
    }
}
