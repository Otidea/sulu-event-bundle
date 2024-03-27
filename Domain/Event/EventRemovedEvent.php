<?php

declare(strict_types=1);

namespace Otd\SuluEventBundle\Domain\Event;

use Otd\SuluEventBundle\Admin\EventAdmin;
use Otd\SuluEventBundle\Entity\EventInterface as Event;
use Sulu\Bundle\ActivityBundle\Domain\Event\DomainEvent;

class EventRemovedEvent extends DomainEvent
{
    private Event $event;

    public function __construct(
        Event $event,
    ) {
        parent::__construct();

        $this->event = $event;
    }

    public function getEventType(): string
    {
        return 'removed';
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

    public function getResourceTitleLocale(): ?string
    {
        return $this->event->getLocale();
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
