<?php
/**
 * Params Entity
 *
 * @link   https://github.com/AriiPortal/JOEBundle
 * @author Bryan Folliot <bfolliot@clever-age.com>
 */

namespace Arii\JOEBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Params
 *
 * @ORM\Table(name="JOE_PARAMS")
 * @ORM\Entity
 */
class Params extends AbstractEntity
{

    /**
     * @ORM\ManyToMany(targetEntity="Param")
     * @ORM\JoinTable(name="JOE_PARAMS_PARAM",
     *      joinColumns={@ORM\JoinColumn(name="params_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="param_id", referencedColumnName="id", unique=true)}
     *      )
     */
    protected $params;

    /**
     * @ORM\ManyToMany(targetEntity="IncludeFile")
     * @ORM\JoinTable(name="JOE_PARAMS_INCLUDES",
     *      joinColumns={@ORM\JoinColumn(name="params_id", referencedColumnName="id")},
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
        $this->params   = new ArrayCollection;
        $this->includes = new ArrayCollection;
        return parent::__construct();
    }

    /**
     * Get params collection
     *
     * @return ArrayCollection
     */
    public function getParams()
    {
        return $this->environmentVariables;
    }

    /**
     * Set params collection
     *
     * @param ArrayCollection params
     *
     * @return self
     */
    public function setParams(ArrayCollection $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Add param in params collection
     *
     * @param Param $lockUse
     *
     * @return self
     */
    public function addParam(Param $param)
    {
        $this->params[] = $param;
        return $this;
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
