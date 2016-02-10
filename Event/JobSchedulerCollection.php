<?php
/**
 * Job Scheduler Collection Event.
 *
 * @link   https://github.com/AriiPortal/JOEBundle
 * @author Bryan Folliot <bfolliot@clever-age.com>
 */

namespace Arii\JOEBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class JobSchedulerCollection extends Event
{
    const ON_FETCH  = 'arii_joe.job_scheduler_collection_event.fetch';

    /**
     * @var \Arii\JOEBundle\Entity\JobScheduler
     */
    protected $collection;

    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Get collection
     *
     * @return array
     */
    public function getCollection()
    {
        return $this->collection;
    }
    
    /**
     * Set collection
     *
     * @param array collection
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;
        return $this;
    }
}
