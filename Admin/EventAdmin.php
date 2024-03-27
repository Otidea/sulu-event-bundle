<?php

declare(strict_types = 1);

namespace Otd\SuluEventBundle\Admin;

use Otd\SuluEventBundle\Entity\EventInterface as Event;
use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItem;
use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItemCollection;
use Sulu\Bundle\AdminBundle\Admin\View\ToolbarAction;
use Sulu\Bundle\AdminBundle\Admin\View\ViewBuilderFactoryInterface;
use Sulu\Bundle\AdminBundle\Admin\View\ViewCollection;
use Sulu\Component\Localization\Manager\LocalizationManagerInterface;
use Sulu\Component\Security\Authorization\PermissionTypes;
use Sulu\Component\Security\Authorization\SecurityCheckerInterface;

class EventAdmin extends Admin
{
    public const SECURITY_CONTEXT = 'otd_sulu.events';
    final public const EVENT_FORM_KEY = 'event_details';
    final public const EVENT_LIST_VIEW = 'otd_sulu.events_list';
    final public const EVENT_ADD_FORM_VIEW = 'otd_sulu.event_add_form';
    final public const EVENT_EDIT_FORM_VIEW = 'otd_sulu.event_edit_form';
    final public const EVENT_PAGE = 'otd_sulu.event_page';

    public function __construct(
        private readonly ViewBuilderFactoryInterface $viewBuilderFactory,
        private readonly LocalizationManagerInterface $localizationManager,
        private readonly SecurityCheckerInterface $securityChecker,
    ) {
    }

    public function configureNavigationItems(NavigationItemCollection $navigationItemCollection): void
    {
        $eventNavigationItem = new NavigationItem('otd_sulu_event.agenda');
        $eventNavigationItem->setIcon('su-calendar');
        $eventNavigationItem->setPosition(30);

        if ($this->securityChecker->hasPermission(static::SECURITY_CONTEXT, PermissionTypes::VIEW)) {
            $events = new NavigationItem('otd_sulu_event.events');
            $events->setPosition(10);
            $events->setView(static::EVENT_LIST_VIEW);

            $eventNavigationItem->addChild($events);

            $categories = new NavigationItem('otd_sulu_event.categories');
            $categories->setPosition(20);
            $categories->setView(static::EVENT_LIST_VIEW);

            $eventNavigationItem->addChild($categories);
        }

        $navigationItemCollection->add($eventNavigationItem);
    }

    public function configureViews(ViewCollection $viewCollection): void
    {
        $locales = $this->localizationManager->getLocales();

        $listView = $this->viewBuilderFactory->createListViewBuilder(static::EVENT_LIST_VIEW, '/:locale/events')
            ->setResourceKey(Event::RESOURCE_KEY)
            ->setListKey('events')
            ->setTitle('otd_sulu_event.events')
            ->addListAdapters(['table'])
            ->addLocales($locales)
            ->setDefaultLocale($locales[0])
            ->setAddView(static::EVENT_ADD_FORM_VIEW)
            ->setEditView(static::EVENT_EDIT_FORM_VIEW);

        // Add toolbar actions for list view if user has corresponding permissions
        if ($this->securityChecker->hasPermission(static::SECURITY_CONTEXT, PermissionTypes::ADD)) {
            $listView->addToolbarActions([new ToolbarAction('sulu_admin.add')]);
        }

        if ($this->securityChecker->hasPermission(static::SECURITY_CONTEXT, PermissionTypes::DELETE)) {
            $listView->addToolbarActions([new ToolbarAction('sulu_admin.delete')]);
        }

        $viewCollection->add($listView);

        if ($this->securityChecker->hasPermission(static::SECURITY_CONTEXT, PermissionTypes::ADD)) {
            // Add form view
            $addFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(
                static::EVENT_ADD_FORM_VIEW,
                '/:locale/events/add',
            )
                ->setResourceKey(Event::RESOURCE_KEY)
                ->addLocales($locales)
                ->setBackView(static::EVENT_LIST_VIEW);

            $viewCollection->add($addFormView);

            // Add details form view
            $addDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(
                static::EVENT_ADD_FORM_VIEW . '.details',
                '/details',
            )
                ->setResourceKey(Event::RESOURCE_KEY)
                ->setFormKey(static::EVENT_FORM_KEY)
                ->setTabTitle('sulu_admin.details')
                ->setEditView(static::EVENT_EDIT_FORM_VIEW)
                ->addToolbarActions([new ToolbarAction('sulu_admin.save')])
                ->setParent(static::EVENT_ADD_FORM_VIEW);

            $viewCollection->add($addDetailsFormView);
        }

        if ($this->securityChecker->hasPermission(static::SECURITY_CONTEXT, PermissionTypes::EDIT)) {
            // Edit form view
            $editFormView = $this->viewBuilderFactory->createResourceTabViewBuilder(
                static::EVENT_EDIT_FORM_VIEW,
                '/:locale/events/edit/:id',
            )
                ->setResourceKey(Event::RESOURCE_KEY)
                ->addLocales($locales)
                ->setBackView(static::EVENT_LIST_VIEW);

            $viewCollection->add($editFormView);

            // Edit details form view
            $editDetailsFormView = $this->viewBuilderFactory->createFormViewBuilder(
                static::EVENT_EDIT_FORM_VIEW . '.details',
                '/details',
            )
                ->setResourceKey(Event::RESOURCE_KEY)
                ->setFormKey(static::EVENT_FORM_KEY)
                ->setTabTitle('sulu_admin.details')
                ->addToolbarActions([new ToolbarAction('sulu_admin.save')])
                ->setParent(static::EVENT_EDIT_FORM_VIEW);

            if ($this->securityChecker->hasPermission(static::SECURITY_CONTEXT, PermissionTypes::DELETE)) {
                $editDetailsFormView->addToolbarActions([new ToolbarAction('sulu_admin.delete')]);
            }

            $viewCollection->add($editDetailsFormView);
        }

        // Edit categories form view
//        $editCategoriesFormView = $this->viewBuilderFactory->createFormViewBuilder(
//            static::EVENT_EDIT_FORM_VIEW . '.categories',
//            '/categories',
//        )
//            ->setResourceKey(Event::RESOURCE_KEY)
//            ->setFormKey('event_categories')
//            ->setTabTitle('otd_sulu_event.categories')
//            ->addToolbarActions([new ToolbarAction('sulu_admin.save'), new ToolbarAction('sulu_admin.delete')])
//            ->setParent(static::EVENT_EDIT_FORM_VIEW);
//
//        $viewCollection->add($editCategoriesFormView);
    }

    public function getSecurityContexts(): array
    {
        return [
            'Sulu' => [
                'Event' => [
                    static::SECURITY_CONTEXT => [
                        PermissionTypes::VIEW,
                        PermissionTypes::ADD,
                        PermissionTypes::EDIT,
                        PermissionTypes::DELETE,
                    ],
                ],
            ],
        ];
    }
}
