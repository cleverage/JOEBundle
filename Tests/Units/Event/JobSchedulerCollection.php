<?php

namespace Arii\JOEBundle\Tests\Units\Event;

use Arii\JOEBundle\Entity\JobScheduler as Entity;
use atoum\AtoumBundle\Test\Units;

class JobSchedulerCollection extends Units\Test
{

    public function testInitService()
    {
        $this->object($this->getInstance())
            ->isTestedInstance();
    }

    public function testGetTarget()
    {
        $this->array($this->getInstance()->getCollection())
            ->isNotEmpty();
    }

    public function testSetTarget()
    {
        $this->object(
            $this->getInstance()->setCollection(
                $this->getTarget()
            )
        )
            ->isTestedInstance();
        $this->testGetTarget();
    }

    protected function getInstance()
    {
        return $this->newTestedInstance(
            $this->getTarget()
        );
    }

    protected function getTarget()
    {
        return array(
            0 => new Entity('hello'),
        );
    }
}
