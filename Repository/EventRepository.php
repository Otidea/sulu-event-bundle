<?php

declare(strict_types=1);

namespace Otd\SuluEventBundle\Repository;

use Doctrine\ORM\NoResultException;
use Otd\SuluEventBundle\Entity\EventInterface as Event;
use Sulu\Component\Persistence\Repository\ORM\EntityRepository;

/**
 * @extends EntityRepository<Event>
 */
class EventRepository extends EntityRepository implements EventRepositoryInterface
{
    public function findEventById($id): ?Event
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.id = :id');

        $query = $qb->getQuery();
        $query->setParameter('id', $id);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $nre) {
            return null;
        }
    }

    public function findEventByName($name): ?Event
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.name = :name');

        $query = $qb->getQuery();
        $query->setParameter('name', $name);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $nre) {
            return null;
        }
    }

    /**
     * @return Event[]
     */
    public function loadAll(string $locale = null): array
    {
        $locale = $locale ?? 'fr';

        $qb = $this->createQueryBuilder('e')
            ->where('e.locale = :locale')
            ->orderBy('e.startDate', 'DESC');

        $query = $qb->getQuery();

        $query->setParameter('locale', $locale);

        return $query->getResult();
    }

    public function findAllEvents(): array
    {
        return $this->findAll();
    }
}
