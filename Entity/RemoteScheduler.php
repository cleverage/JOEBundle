<?php
/**
 * RemoteScheduler Entity
 *
 * @link   https://github.com/AriiPortal/JOEBundle
 * @author Bryan Folliot <bfolliot@clever-age.com>
 */

namespace Arii\JOEBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RemoteScheduler
 *
 * @ORM\Table(name="JOE_REMOTE_SCHEDULER")
 * @ORM\Entity
 */
class RemoteScheduler extends AbstractEntity
{


    /**
     * @var integer
     *
     * @ORM\Column(name="http_heartbeat_period", type="integer")
     */
    protected $httpHeartbeatPeriod = 10;

    /**
     * @var integer
     *
     * @ORM\Column(name="http_heartbeat_timeout", type="integer")
     */
    protected $httpHeartbeatTimeout = 15;

    /**
     * @var string
     *
     * @Assert\Length(max=255)
     * @ORM\Column(name="remote_scheduler", type="string", length=255)
     */
    protected $remoteScheduler;
}
