<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConstituencyCandidacy
 */
class ConstituencyCandidacy
{
    /**
     * @var \Btw\Bundle\PersistenceBundle\Entity\Candidate
     */
    private $candidate;

    /**
     * @var \Btw\Bundle\PersistenceBundle\Entity\Constituency
     */
    private $constituency;


    /**
     * Set candidate
     *
     * @param \Btw\Bundle\PersistenceBundle\Entity\Candidate $candidate
     * @return ConstituencyCandidacy
     */
    public function setCandidate(\Btw\Bundle\PersistenceBundle\Entity\Candidate $candidate = null)
    {
        $this->candidate = $candidate;
    
        return $this;
    }

    /**
     * Get candidate
     *
     * @return \Btw\Bundle\PersistenceBundle\Entity\Candidate 
     */
    public function getCandidate()
    {
        return $this->candidate;
    }

    /**
     * Set constituency
     *
     * @param \Btw\Bundle\PersistenceBundle\Entity\Constituency $constituency
     * @return ConstituencyCandidacy
     */
    public function setConstituency(\Btw\Bundle\PersistenceBundle\Entity\Constituency $constituency = null)
    {
        $this->constituency = $constituency;
    
        return $this;
    }

    /**
     * Get constituency
     *
     * @return \Btw\Bundle\PersistenceBundle\Entity\Constituency 
     */
    public function getConstituency()
    {
        return $this->constituency;
    }
}
