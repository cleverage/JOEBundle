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


    protected function error(Exception $e, array $args)
    {
        $payload = $this->payloadFactory->newInstance();
        return $payload
            ->setStatus(PayloadStatus::ERROR)
            ->setInput($args)
            ->setOutput($e);
    }
}
