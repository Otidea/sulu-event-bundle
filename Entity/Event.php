<?php

declare(strict_types = 1);

namespace Otd\SuluEventBundle\Entity;

use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Sulu\Component\Security\Authentication\UserInterface;

/**
 * Represents single event in the system.
 */
class Event implements EventInterface
{
    public const RESOURCE_KEY = 'events';

    /**
     * @Groups({"partialEvent"})
     */
    protected int $id;

    /**
     * @Groups({"partialEvent"})
     */
    protected \DateTime $created;

    /**
     * @Groups({"partialEvent"})
     */
    protected \DateTime $changed;

    /**
     * @Expose
     *
     * @Groups({"partialEvent"})
     */
    protected string $name;

    /**
     * @Groups({"partialEvent"})
     */
    protected \DateTime $startDate;

    /**
     * @Groups({"partialEvent"})
     */
    protected \DateTime $endDate;

    /**
     * @Groups({"partialEvent"})
     */
    protected string $locale;

    /**
     * @var UserInterface|null
     *
     * @Groups({"partialEvent"})
     */
    protected ?UserInterface $creator;

    /**
     * @var UserInterface|null
     *
     * @Groups({"partialEvent"})
     */
    protected ?UserInterface $changer;

    public function __construct()
    {
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setCreated(\DateTime $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    public function setChanged(\DateTime $changed): self
    {
        $this->changed = $changed;

        return $this;
    }

    public function getChanged(): \DateTime
    {
        return $this->changed;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setStartDate(\DateTime $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function setEndDate(\DateTime $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function setCreator(UserInterface $creator = null): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getCreator(): ?UserInterface
    {
        return $this->creator;
    }

    public function setChanger(UserInterface $changer = null): self
    {
        $this->changer = $changer;

        return $this;
    }

    public function getChanger(): ?UserInterface
    {
        return $this->changer;
    }
}
