<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConstituencyCandidacy
 *
 * @ORM\Table(name="constituency_candidacy")
 * @ORM\Entity
 */
class ConstituencyCandidacy
{
	/**
	 * @var \Btw\Bundle\PersistenceBundle\Entity\Candidate
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="NONE")
	 * @ORM\OneToOne(targetEntity="Btw\Bundle\PersistenceBundle\Entity\Candidate")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="candidate_id", referencedColumnName="candidate_id", unique=true)
	 * })
	 */
	private $candidate;

	/**
	 * @var \Btw\Bundle\PersistenceBundle\Entity\Constituency
	 *
	 * @ORM\ManyToOne(targetEntity="Btw\Bundle\PersistenceBundle\Entity\Constituency")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="constituency_id", referencedColumnName="constituency_id")
	 * })
	 */
	private $constituency;

	/**
	 * @param mixed $candidate
	 */
	public function setCandidate(Candidate $candidate)
	{
		$this->candidate = $candidate;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCandidate()
	{
		return $this->candidate;
	}

	/**
	 * @param mixed $constituency
	 */
	public function setConstituency(Constituency $constituency)
	{
		$this->constituency = $constituency;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getConstituency()
	{
		return $this->constituency;
	}


}
