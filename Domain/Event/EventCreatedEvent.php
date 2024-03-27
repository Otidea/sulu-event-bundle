<?php

declare(strict_types=1);

namespace Otd\SuluEventBundle\Domain\Event;

use Otd\SuluEventBundle\Admin\EventAdmin;
use Otd\SuluEventBundle\Entity\EventInterface as Event;
use Sulu\Bundle\ActivityBundle\Domain\Event\DomainEvent;

class EventCreatedEvent extends DomainEvent
{
    private Event $event;

    private array $payload;

    public function __construct(
        Event $event,
        array $payload,
    ) {
        parent::__construct();

        $this->event = $event;
        $this->payload = $payload;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function getEventType(): string
    {
        return 'created';
    }

    public function getEventPayload(): ?array
    {
        return $this->payload;
    }

    public function getResourceKey(): string
    {
        return Event::RESOURCE_KEY;
    }

    public function getResourceId(): string
    {
        return (string) $this->event->getId();
    }

    public function getResourceTitle(): ?string
    {
        return $this->event->getName();
    }

    public function getUserFullName(): ?string
    {
        return $this->event->getChanger()->getFullName();
    }

    public function getResourceSecurityContext(): ?string
    {
        return EventAdmin::SECURITY_CONTEXT;
    }
}
