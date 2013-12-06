<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StateCandidacy
 *
 * @ORM\Table(name="state_candidacy")
 * @ORM\Entity
 */
class StateCandidacy
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="position", type="integer", nullable=false)
	 */
	private $position;

	/**
	 * @var Candidate
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="NONE")
	 * @ORM\OneToOne(targetEntity="Candidate")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="candidate_id", referencedColumnName="candidate_id")
	 * })
	 */
	private $candidate;

	/**
	 * @var StateList
	 *
	 * @ORM\ManyToOne(targetEntity="StateList")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="state_list_id", referencedColumnName="state_list_id")
	 * })
	 */
	private $stateList;

	/**
	 * Constructor
	 *
	 * @param Candidate $candidate
	 * @param StateList $stateList
	 * @param int       $position
	 */
	function __construct(Candidate $candidate, StateList $stateList, $position)
	{
		$this->candidate = $candidate;
		$this->stateList = $stateList;
		$this->position  = $position;
	}

	/**
	 * @param Candidate $candidate
	 *
	 * @return StateCandidacy
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
	 * @param int $position
	 *
	 * @return StateCandidacy
	 */
	public function setPosition($position)
	{
		$this->position = $position;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * @param StateList $stateList
	 *
	 * @return StateCandidacy
	 */
	public function setStateList(StateList $stateList)
	{
		$this->stateList = $stateList;
		return $this;
	}

	/**
	 * @return StateList
	 */
	public function getStateList()
	{
		return $this->stateList;
	}

}
