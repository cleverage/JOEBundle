<?php

namespace Arii\JOEBundle\Tests\Units\Event;

use Arii\JOEBundle\Entity\JobScheduler as Entity;
use atoum\AtoumBundle\Test\Units;

class JobScheduler extends Units\Test
{

    public function testInitService()
    {
        $this->object($this->getInstance())
            ->isTestedInstance();
    }

    public function testGetTarget()
    {
        $this->object($this->getInstance()->getJobScheduler())
            ->isInstanceOf(Entity::class);
    }

    public function testSetTarget()
    {
        $this->object(
            $this->getInstance()->setJobScheduler(
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
        return new Entity('hello');
    }
}
