<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Represents the link between a candidate and the constituency he candidates in.
 *
 * @ORM\Table(name="constituency_candidacy")
 * @ORM\Entity
 */
class ConstituencyCandidacy
{

	/**
	 * @var Candidate
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
	 * @var Constituency
	 *
	 * @ORM\ManyToOne(targetEntity="Btw\Bundle\PersistenceBundle\Entity\Constituency")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="constituency_id", referencedColumnName="constituency_id")
	 * })
	 */
	private $constituency;

	/**
	 * @param Candidate $candidate
	 *
	 * @return $this
	 */
	public function setCandidate(Candidate $candidate)
	{
		$this->candidate = $candidate;
		return $this;
	}

	/**
	 * @return Candidate
	 */
	public function getCandidate()
	{
		return $this->candidate;
	}

	/**
	 * @param Constituency $constituency
	 *
	 * @return $this
	 */
	public function setConstituency(Constituency $constituency)
	{
		$this->constituency = $constituency;
		return $this;
	}

	/**
	 * @return Constituency
	 */
	public function getConstituency()
	{
		return $this->constituency;
	}

}
