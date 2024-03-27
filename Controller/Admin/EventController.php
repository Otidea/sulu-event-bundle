<?php

declare(strict_types = 1);

namespace Otd\SuluEventBundle\Controller\Admin;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\View\ViewHandlerInterface;
use HandcraftedInTheAlps\RestRoutingBundle\Routing\ClassResourceInterface;
use Otd\SuluEventBundle\Admin\EventAdmin;
use Otd\SuluEventBundle\Controller\Admin\Exception\ConstraintViolationException;
use Otd\SuluEventBundle\Entity\Event as EventEntity;
use Otd\SuluEventBundle\Entity\EventInterface as Event;
use Otd\SuluEventBundle\Exception\EventAlreadyExistsException;
use Otd\SuluEventBundle\Exception\EventNotFoundException;
use Otd\SuluEventBundle\Manager\EventManager;
use Sulu\Component\Rest\AbstractRestController;
use Sulu\Component\Rest\Exception\EntityNotFoundException;
use Sulu\Component\Rest\Exception\MissingArgumentException;
use Sulu\Component\Rest\Exception\RestException;
use Sulu\Component\Rest\ListBuilder\Doctrine\DoctrineListBuilderFactoryInterface;
use Sulu\Component\Rest\ListBuilder\Metadata\FieldDescriptorFactoryInterface;
use Sulu\Component\Rest\ListBuilder\PaginatedRepresentation;
use Sulu\Component\Rest\RequestParametersTrait;
use Sulu\Component\Rest\RestHelperInterface;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @RouteResource("event")
 */
class EventController extends AbstractRestController implements ClassResourceInterface, SecuredControllerInterface
{
    use RequestParametersTrait;

    protected static $entityName = 'OtdSuluEventBundle:Event';

    public function __construct(
        private readonly ViewHandlerInterface $viewHandler,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly FieldDescriptorFactoryInterface $fieldDescriptorFactory,
        private readonly DoctrineListBuilderFactoryInterface $listBuilderFactory,
        private readonly RestHelperInterface $restHelper,
        private readonly EventManager $eventManager,
    ) {
        parent::__construct($viewHandler, $tokenStorage);
    }

    /**
     * Returns all events.
     */
    public function cgetAction(): Response
    {
        $fieldDescriptors = $this->fieldDescriptorFactory->getFieldDescriptors(Event::RESOURCE_KEY);
        $list = $this->listBuilderFactory->create(EventEntity::class);
        $this->restHelper->initializeListBuilder($list, $fieldDescriptors);

        $representation = new PaginatedRepresentation(
            $list->execute(),
            Event::RESOURCE_KEY,
            (int)$list->getCurrentPage(),
            (int)$list->getLimit(),
            $list->count(),
        );

        return $this->handleView($this->view($representation));
    }

    /**
     * Returns a single event.
     */
    public function getAction(int $id): Response
    {
//        dd($this->eventManager->findById($id)->getCreator());
//        $view = $this->responseGetById(
//            $id,
//            function ($id) {
//                return $this->eventManager->findById($id);
//            },
//        );
        $view = $this->responseGetById(
            $id,
            function ($id) {
                return $this->getApiEntity($this->eventManager->findById($id));
            },
        );

        $context = new Context();
        $context->setGroups(['partialEvent']);
        $view->setContext($context);

        return $this->handleView($view);
    }

    /**
     * Creates a new event.
     */
    public function postAction(Request $request): Response
    {
        return $this->saveEvent($request);
    }

    /**
     * Updates an existing event.
     */
    public function putAction(Request $request, int $id): Response
    {
        return $this->saveEvent($request, $id);
    }

    /**
     * Updates an existing event.
     */
    public function patchAction(Request $request, int $id): Response
    {
        return $this->saveEvent($request, $id);
    }

    /**
     * Deletes an existing event.
     */
    public function deleteAction(int $id): Response
    {
        $delete = function ($id) {
            try {
                $this->eventManager->delete($id);
            } catch (EventNotFoundException $tnfe) {
                throw new EntityNotFoundException(self::$entityName, $id, $tnfe);
            }
        };

        $view = $this->responseDelete($id, $delete);

        return $this->handleView($view);
    }

    /**
     * Save an event.
     */
    protected function saveEvent(Request $request, int $id = null): Response
    {
        $name = $request->get('name');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');

        try {
            $locale = $this->getRequestParameter($request, 'locale', true);
        } catch (\Exception $e) {
            $locale = 'fr';
        }

        try {
            if (empty($name)) {
                throw new MissingArgumentException(self::$entityName, 'name');
            }

            if (empty($startDate)) {
                throw new MissingArgumentException(self::$entityName, 'startDate');
            }

            if (empty($endDate)) {
                throw new MissingArgumentException(self::$entityName, 'endDate');
            }

            $event = $this->eventManager->save($this->getData($request), $id, $locale);

            $context = new Context();
            $context->setGroups(['partialEvent']);
            $view = $this->view($this->getApiEntity($event))->setContext($context);
        } catch (EventAlreadyExistsException $exc) {
            $cvExistsException = new ConstraintViolationException(
                'A Event with the name "' . $exc->getName() . '"already exists!',
                'name',
                ConstraintViolationException::EXCEPTION_CODE_NON_UNIQUE_NAME,
            );
            $view = $this->view($cvExistsException->toArray(), 400);
        } catch (RestException $exc) {
            $view = $this->view($exc->toArray(), 400);
        }

        return $this->handleView($view);
    }

    /**
     * Get data.
     */
    protected function getData(Request $request): array
    {
        return $request->request->all();
    }

    /**
     * TODO use serializer.
     */
    private function getApiEntity(Event $entity): array
    {
        // Api Entity
        return [
            'id' => $entity->getId(),
            'name' => $entity->getName(),
            'startDate' => $entity->getStartDate()->format('Y-m-d H:i:s'),
            'endDate' => $entity->getEndDate()->format('Y-m-d H:i:s'),
            'locale' => $entity->getLocale(),
            'creator' => $entity->getCreator()->getId(),
            'created' => $entity->getCreated()->format('Y-m-d H:i:s'),
            'changer' => $entity->getChanger()->getId(),
            'changed' => $entity->getChanged()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Returns the security context.
     */
    public function getSecurityContext(): string
    {
        return EventAdmin::SECURITY_CONTEXT;
    }
}
