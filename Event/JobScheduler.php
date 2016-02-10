<?php
/**
 * Job Scheduler Event.
 *
 * @link   https://github.com/AriiPortal/JOEBundle
 * @author Bryan Folliot <bfolliot@clever-age.com>
 */

namespace Arii\JOEBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class JobScheduler extends Event
{
    const ON_CREATE = 'arii_joe.job_scheduler_event.create';
    const ON_DELETE = 'arii_joe.job_scheduler_event.delete';
    const ON_FETCH  = 'arii_joe.job_scheduler_event.fetch';
    const ON_UPDATE = 'arii_joe.job_scheduler_event.update';

    /**
     * @var \Arii\JOEBundle\Entity\JobScheduler
     */
    protected $jobScheduler;

    public function __construct($jobScheduler)
    {
        $this->jobScheduler = $jobScheduler;
    }

    /**
     * Get jobScheduler
     *
     * @return \Arii\JOEBundle\Entity\JobScheduler
     */
    public function getJobScheduler()
    {
        return $this->jobScheduler;
    }
    
    /**
     * Set jobScheduler
     *
     * @param \Arii\JOEBundle\Entity\JobScheduler jobScheduler
     */
    public function setJobScheduler($jobScheduler)
    {
        $this->jobScheduler = $jobScheduler;
        return $this;
    }
}
