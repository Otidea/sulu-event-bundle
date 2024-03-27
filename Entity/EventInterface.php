<?php

declare(strict_types = 1);

namespace Otd\SuluEventBundle\Entity;

use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Security\Authentication\UserInterface;

interface EventInterface extends AuditableInterface
{
    public const RESOURCE_KEY = 'events';

    /**
     * Get id.
     */
    public function getId(): int;

    /**
     * Set id.
     */
    public function setId(int $id): self;

    /**
     * Set name.
     */
    public function setName(string $name): self;

    /**
     * Get name.
     */
    public function getName(): string;

    /**
     * Set startDate.
     */
    public function setStartDate(\DateTime $startDate): self;

    /**
     * Get startDate.
     */
    public function getStartDate(): \DateTime;

    /**
     * Set endDate.
     */
    public function setEndDate(\DateTime $endDate): self;

    /**
     * Get endDate.
     */
    public function getEndDate(): \DateTime;

    /**
     * Set locale.
     */
    public function setLocale(string $locale): self;

    /**
     * Get locale.
     */
    public function getLocale(): string;

    /**
     * Set creator.
     * Note: This property is set automatically by the UserBlameSubscriber if not set manually.
     */
    public function setCreator(UserInterface $creator = null): self;

    /**
     * Set created.
     * Note: This property is set automatically by the TimestampableSubscriber if not set manually.
     */
    public function setCreated(\DateTime $created): self;

    /**
     * Set changer.
     * Note: This property is set automatically by the UserBlameSubscriber if not set manually.
     */
    public function setChanger(UserInterface $changer = null): self;

    /**
     * Set changed.
     * Note: This property is set automatically by the TimestampableSubscriber if not set manually.
     */
    public function setChanged(\DateTime $changed): self;
}
