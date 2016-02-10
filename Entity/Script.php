<?php
/**
 * Script Entity
 *
 * @link   https://github.com/AriiPortal/JOEBundle
 * @author Bryan Folliot <bfolliot@clever-age.com>
 */

namespace Arii\JOEBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Script
 *
 * @ORM\Table(name="ARII_JOE_SCRIPT")
 * @ORM\Entity
 */
class Script extends AbstractEntity
{

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     * @ORM\Column(name="language", type="string", length=255)
     */
    protected $language;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     * @ORM\Column(name="com_class", type="string", length=255)
     */
    protected $comClass;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     * @ORM\Column(name="filename", type="string", length=255)
     */
    protected $filename;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     * @ORM\Column(name="java_class", type="string", length=255)
     */
    protected $javaClass;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     * @ORM\Column(name="java_class_path", type="string", length=255)
     */
    protected $javaClassPath;

    /**
     * @ORM\ManyToMany(targetEntity="IncludeFile")
     * @ORM\JoinTable(name="ARII_JOE_SCRIPT_INCLUDES",
     *      joinColumns={@ORM\JoinColumn(name="script_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="include_id", referencedColumnName="id", unique=true)}
     *      )
     */
    protected $includes;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->includes = new ArrayCollection;
        return parent::__construct();
    }

    /**
     * Get includes collection
     *
     * @return ArrayCollection
     */
    public function getIncludes()
    {
        return $this->includes;
    }

    /**
     * Set includes collection
     *
     * @param ArrayCollection includes
     *
     * @return self
     */
    public function setIncludes(ArrayCollection $includes)
    {
        $this->includes = $includes;
        return $this;
    }

    /**
     * Add include in includes collection
     *
     * @param IncludeFile $include
     *
     * @return self
     */
    public function addInclude(IncludeFile $include)
    {
        $this->includes[] = $param;
        return $this;
    }
}
