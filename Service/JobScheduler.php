<?php
/**
 * Job Scheduler Service.
 *
 * @link   https://github.com/AriiPortal/JOEBundle
 * @author Bryan Folliot <bfolliot@clever-age.com>
 */

namespace Arii\JOEBundle\Service;

use Arii\JOEBundle\Entity\JobScheduler as Entity;
use Arii\JOEBundle\Event\JobScheduler as Event;
use Arii\JOEBundle\Event\JobSchedulerCollection as CollectionEvent;
use Aura\Payload\PayloadFactory;
use Aura\Payload_Interface\PayloadStatus;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JobScheduler
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
     * @param string $name
     * @return Aura\Payload\Payload
     */
    public function create($name)
    {
        $payload = $this->payloadFactory->newInstance();
        
        try {
            $entity = new Entity($name);
            $errors = $this->validator->validate($entity);

            if (count($errors) > 0) {
                return $payload
                    ->setStatus(PayloadStatus::NOT_VALID)
                    ->setInput($name)
                    ->setOutput($entity)
                    ->setMessages($errors);
            }


            $event = new Event($entity);
            $this->eventDispatcher->dispatch(Event::ON_CREATE, $event);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            return $payload
                ->setStatus(PayloadStatus::CREATED)
                ->setOutput($entity);

        } catch (Exception $e) {
            return $this->error($e, func_get_args());
        }
    }

    /**
     * @param Ramsey\Uuid\Uuid $id
     * @return Aura\Payload\Payload
     */
    public function fetch(Uuid $id)
    {
        $payload = $this->payloadFactory->newInstance();

        try {

            $entity = $this->entityManager
                ->getRepository('AriiJOEBundle:JobScheduler')
                ->find($id);

            if (!$entity) {
                return $payload
                    ->setStatus(PayloadStatus::NOT_FOUND)
                    ->setInput(func_get_args());
            }


            $event = new Event($entity);
            $this->eventDispatcher->dispatch(Event::ON_FETCH, $event);

            return $payload
                ->setStatus(PayloadStatus::FOUND)
                ->setOutput($entity);

        } catch (Exception $e) {
            return $this->error($e, func_get_args());
        }
    }

    /**
     * @return Aura\Payload\Payload
     */
    public function fetchAll()
    {
        $payload = $this->payloadFactory->newInstance();
        try {
            $collection = $this->entityManager
                ->getRepository('AriiJOEBundle:JobScheduler')
                ->findAll();

            $event = new CollectionEvent($collection);
            $this->eventDispatcher->dispatch(CollectionEvent::ON_FETCH, $event);

            if (!$collection) {
                return $payload
                    ->setStatus(PayloadStatus::NOT_FOUND)
                    ->setInput(func_get_args());
            }


            return $payload
                ->setStatus(PayloadStatus::FOUND)
                ->setOutput($collection);
        } catch (Exception $e) {
            return $this->error($e, func_get_args());
        }
    }

    /**
     * @param Arii\JOEBundle\Entity\JobScheduler $entity
     * @return Aura\Payload\Payload
     */
    public function update(Entity $entity)
    {
        $payload = $this->payloadFactory->newInstance();
        try {
            $errors = $this->validator->validate($entity);
            if (count($errors) > 0) {
                return $payload
                    ->setStatus(PayloadStatus::NOT_VALID)
                    ->setInput($entity)
                    ->setOutput($entity)
                    ->setMessages($errors);
            }

            $entity = $this->entityManager->merge($entity);
            $this->entityManager->flush();

            $event = new Event($entity);
            $this->eventDispatcher->dispatch(Event::ON_UPDATE, $event);

            return $payload
                ->setStatus(PayloadStatus::UPDATED)
                ->setOutput($entity);
        } catch (Exception $e) {
            return $this->error($e, func_get_args());
        }
    }

    /**
     * @param Arii\JOEBundle\Entity\JobScheduler $entity
     * @return Aura\Payload\Payload
     */
    public function delete(Entity $entity)
    {
        $payload = $this->payloadFactory->newInstance();
        try {
            $event = new Event($entity);
            $this->eventDispatcher->dispatch(Event::ON_DELETE, $event);
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
            return $payload
                ->setStatus(PayloadStatus::DELETED)
                ->setOutput($entity);
        } catch (Exception $e) {
            return $this->error($e, func_get_args());
        }
    }

    protected function error(Exception $e, array $args)
    {
        $payload = $this->payloadFactory->newInstance();
        return $payload
            ->setStatus(PayloadStatus::ERROR)
            ->setInput($args)
            ->setOutput($e);
    }
}
