<?php

declare(strict_types = 1);

namespace Otd\SuluEventBundle\Manager;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Otd\SuluEventBundle\Domain\Event\EventCreatedEvent;
use Otd\SuluEventBundle\Domain\Event\EventModifiedEvent;
use Otd\SuluEventBundle\Domain\Event\EventRemovedEvent;
use Otd\SuluEventBundle\Entity\EventInterface as Event;
use Otd\SuluEventBundle\Entity\Factory\AbstractFactory;
use Otd\SuluEventBundle\Exception\EventAlreadyExistsException;
use Otd\SuluEventBundle\Exception\EventNotFoundException;
use Otd\SuluEventBundle\Repository\EventRepository;
use Sulu\Bundle\ActivityBundle\Application\Collector\DomainEventCollectorInterface;
use Sulu\Bundle\TrashBundle\Application\TrashManager\TrashManagerInterface;
use Sulu\Component\Security\Authentication\UserRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventManager extends AbstractFactory
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EventRepository $eventRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly DomainEventCollectorInterface $domainEventCollector,
        private readonly ?TrashManagerInterface $trashManager = null,
    ) {
    }

    public function findById(int $id): Event
    {
        if (!$entity = $this->eventRepository->findEventById($id)) {
            throw new EventNotFoundException($id);
        }

        return $entity;
    }

    /**
     * @return Event[]|null
     */
    public function findAll(string $locale = null): ?array
    {
        return $this->eventRepository->loadAll($locale);
    }

    public function save(array $data, ?int $id, string $locale): Event
    {
        $name = $data['name'];
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];

        try {
            // Load existing event if id is given and create new event otherwise.
            if ($id) {
                $event = $this->eventRepository->find($id);
                if (!$event) {
                    throw new EventNotFoundException($id);
                }
            } else {
                $event = $this->eventRepository->createNew();
            }

            $event->setName($name);
            $event->setStartDate(new \DateTime($startDate));
            $event->setEndDate(new \DateTime($endDate));
            $event->setLocale($locale);

            if (!$id) {
                $this->em->persist($event);
                $this->domainEventCollector->collect(new EventCreatedEvent($event, $data));
            } else {
                $this->domainEventCollector->collect(new EventModifiedEvent($event, $data));
            }

            $this->em->flush();

            return $event;
        } catch (UniqueConstraintViolationException $exc) {
            throw new EventAlreadyExistsException($name, $exc);
        }
    }

    public function delete(int $id): void
    {
        if (!$entity = $this->eventRepository->findEventById($id)) {
            throw new EventNotFoundException($id);
        }

        // If the trash-manager is available, we use it to store the entity in the trash.
        $this->trashManager?->store(Event::RESOURCE_KEY, $entity);

        $this->em->remove($entity);
        $this->domainEventCollector->collect(new EventRemovedEvent($id, $entity->getName(), $entity->getLocale()));
        $this->em->flush();
    }
}
