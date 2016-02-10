<?php
/**
 * Job Scheduler Service.
 *
 * @link   https://github.com/AriiPortal/JOEBundle
 * @author Bryan Folliot <bfolliot@clever-age.com>
 */

namespace Arii\JOEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * JobScheduler
 *
 * @ORM\Table(name="ARII_JOE_JOB_SCHEDULER")
 * @UniqueEntity(fields={"id"})
 * @UniqueEntity(fields={"name"})
 * @ORM\Entity
 */
class JobScheduler
{
    
    /**
     * @var \Ramsey\Uuid\Uuid
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    protected $name;

    /**
     * Constructor
     *
     * @param string $name optional name.
     */
    public function __construct($name = null)
    {
        $this->id   = Uuid::uuid4();
        $this->name = $name;
        return $this;
    }

    /**
     * Get Id
     *
     * @return \Ramsey\Uuid\Uuid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set name
     *
     * @param string name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}
