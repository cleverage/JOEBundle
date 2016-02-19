<?php
/**
 * Job Service.
 *
 * @link   https://github.com/AriiPortal/JOEBundle
 * @author Bryan Folliot <bfolliot@clever-age.com>
 */

namespace Arii\JOEBundle\Service;

use Arii\JOEBundle\Entity\Job as Entity;
use Arii\JOEBundle\Event\Job as Event;
use Arii\JOEBundle\Event\JobCollection as CollectionEvent;
use Aura\Payload\PayloadFactory;
use Aura\Payload_Interface\PayloadStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Job
{

    protected $entityManager;
    protected $eventDispatcher;
    protected $payloadFactory;
    protected $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        PayloadFactory $payloadFactory,
        ValidatorInterface $validator
    ) {
        $this->entityManager   = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->payloadFactory  = $payloadFactory;
        $this->validator       = $validator;
    }

    /**
     * @param Arii\JOEBundle\Entity\Job $entity
     * @return Aura\Payload\Payload
     */
    public function create(Entity $entity)
    {
        $payload = $this->payloadFactory
            ->newInstance()
            ->setInput($entity);

        $this->eventDispatcher->dispatch(
            Event::ON_CREATE_PRE,
            new Event($payload)
        );

        // Validate.
        $errors = $this->validator->validate($entity);

        if (count($errors) > 0) {
            $payload->setStatus(PayloadStatus::NOT_VALID)
                ->setOutput($entity)
                ->setMessages($errors);
            $this->eventDispatcher->dispatch(
                Event::ON_CREATE_ERROR,
                new Event($payload)
            );
            return $payload;
        }

        // Save.
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $payload->setStatus(PayloadStatus::CREATED)
            ->setOutput($entity);
        $this->eventDispatcher->dispatch(
            Event::ON_CREATE_POST,
            new Event($payload)
        );

        return $payload;
    }

    /**
     * Return new JobScheduler entity.
     *
     * @return \Arii\JOEBundle\Entity\Job
     */
    public function getNew()
    {
        return new Entity();
    }

    /**
     * @param Ramsey\Uuid\Uuid $id
     * @return Aura\Payload\Payload
     */
    public function fetch(Uuid $id)
    {
        $payload = $this->payloadFactory
            ->newInstance()
            ->setInput($id);

        $this->eventDispatcher->dispatch(
            Event::ON_FETCH_PRE,
            new Event($payload)
        );

        $entity = $this->entityManager
            ->getRepository('AriiJOEBundle:Job')
            ->find($id);

        if (!$entity) {
            $payload
                ->setStatus(PayloadStatus::NOT_FOUND);
            $this->eventDispatcher->dispatch(
                Event::ON_FETCH_ERROR,
                new Event($payload)
            );
            if ($payload->getStatus() == PayloadStatus::FOUND
                && $payload->getOutput() instanceof Entity) {
                return $payload;
            }
            return $payload;
        }
        $payload
            ->setStatus(PayloadStatus::FOUND)
            ->setOutput($entity);

        $this->eventDispatcher->dispatch(
            Event::ON_FETCH_POST,
            new Event($payload)
        );
        return $payload;
    }

    /**
     * @return Aura\Payload\Payload
     */
    public function fetchAll()
    {
        $payload = $this->payloadFactory->newInstance();

        $this->eventDispatcher->dispatch(
            CollectionEvent::ON_FETCH_PRE,
            new CollectionEvent($payload)
        );

        $collection = $this->entityManager
            ->getRepository('AriiJOEBundle:Job')
            ->findAll();

        if (!$collection) {
            $payload->setStatus(PayloadStatus::NOT_FOUND);
            $this->eventDispatcher->dispatch(
                CollectionEvent::ON_FETCH_ERROR,
                new CollectionEvent($payload)
            );
            if ($payload->getStatus() == PayloadStatus::FOUND
                && $payload->getOutput() instanceof ArrayCollection) {
                return $payload;
            }
            return $payload;
        }


        $payload
            ->setStatus(PayloadStatus::FOUND)
            ->setOutput($collection);

        $this->eventDispatcher->dispatch(
            CollectionEvent::ON_FETCH_POST,
            new CollectionEvent($payload)
        );
        return $payload;
    }

    /**
     * @param Arii\JOEBundle\Entity\Job $entity
     * @return Aura\Payload\Payload
     */
    public function update(Entity $entity)
    {
        $payload = $this->payloadFactory
            ->newInstance()
            ->setInput($entity);

        $this->eventDispatcher->dispatch(
            Event::ON_UPDATE_PRE,
            new Event($payload)
        );

        $errors = $this->validator->validate($entity);
        if (count($errors) > 0) {
            $payload
                ->setStatus(PayloadStatus::NOT_VALID)
                ->setOutput($entity)
                ->setMessages($errors);
            $this->eventDispatcher->dispatch(
                Event::ON_UPDATE_ERROR,
                new Event($payload)
            );
            return $payload;
        } else {
            $this->eventDispatcher->dispatch(
                Event::ON_UPDATE_VALID,
                new Event($payload)
            );
        }

        $entity = $this->entityManager->merge($entity);
        $this->entityManager->flush();

        $payload
            ->setStatus(PayloadStatus::UPDATED)
            ->setOutput($entity);

        $this->eventDispatcher->dispatch(
            Event::ON_UPDATE_POST,
            new Event($payload)
        );

        return $payload;
    }

    /**
     * @param Arii\JOEBundle\Entity\Job $entity
     * @return Aura\Payload\Payload
     */
    public function delete(Entity $entity)
    {
        $payload = $this->payloadFactory
            ->newInstance()
            ->setInput($entity);

        $this->eventDispatcher->dispatch(
            Event::ON_DELETE_PRE,
            new Event($payload)
        );

        $this->entityManager->remove($entity);
        $this->entityManager->flush();
        $payload
            ->setStatus(PayloadStatus::DELETED);

        $this->eventDispatcher->dispatch(
            Event::ON_DELETE_POST,
            new Event($payload)
        );

        return $payload;
    }
}
