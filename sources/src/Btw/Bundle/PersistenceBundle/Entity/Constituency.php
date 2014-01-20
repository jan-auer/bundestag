<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * A constituency represents the subunit of a state. Each potential voter is registered in one of the 299 constituencies
 * and must not vote in a different constituency. Each party is allowed to field one candidate (see
 * {@link ConstituencyCandidacy}) who receives a seat in the Bundestag instantly, if he has the most votes in his
 * constituency.
 *
 * As constituencies are determined by their population, they change for each election. Old and new versions of
 * constituencies can be matched by their names.
 *
 * @ORM\Table(name="constituency")
 * @ORM\Entity
 */
class Constituency
{

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="constituency_id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="constituency_constituency_id_seq", allocationSize=1, initialValue=1)
	 */
	private $id;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="number", type="integer", nullable=false)
	 */
	private $number;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="text", nullable=true)
	 */
	private $name;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="electives", type="integer", nullable=false)
	 */
	private $electives;

	/**
	 * @var State
	 *
	 * @ORM\ManyToOne(targetEntity="State")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="state_id", referencedColumnName="state_id")
	 * })
	 */
	private $state;

	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="Candidate", mappedBy="constituency")
	 */
	private $candidates;

	/**
	 * @var Election
	 *
	 * @ORM\ManyToOne(targetEntity="Election")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="election_id", referencedColumnName="election_id")
	 * })
	 */
	private $election;

	/**
	 * @return string
	 */
	public function __toString()
	{
		return sprintf('%s: %s', $this->getNumber(), $this->getName());
	}

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->candidates = new ArrayCollection();
	}

	/**
	 * @param int $id
	 *
	 * @return Constituency
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param string $name
	 *
	 * @return Constituency
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param int $number
	 *
	 * @return Constituency
	 */
	public function setNumber($number)
	{
		$this->number = $number;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getNumber()
	{
		return $this->number;
	}

	/**
	 * @param State $state
	 *
	 * @return Constituency
	 */
	public function setState(State $state)
	{
		$this->state = $state;
		return $this;
	}

	/**
	 * @return State
	 */
	public function getState()
	{
		return $this->state;
	}

	/**
	 * @param Collection $candidates
	 *
	 * @return Constituency
	 */
	public function setCandidates($candidates)
	{
		$this->candidates = $candidates;
		return $this;
	}

	/**
	 * @return Collection
	 */
	public function getCandidates()
	{
		return $this->candidates;
	}

	/**
	 * @param int $electives
	 *
	 * @return Constituency
	 */
	public function setElectives($electives)
	{
		$this->electives = $electives;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getElectives()
	{
		return $this->electives;
	}

	/**
	 * @param Election $election
	 *
	 * @return Constituency
	 */
	public function setElection(Election $election)
	{
		$this->election = $election;
		return $this;
	}

	/**
	 * @return Election
	 */
	public function getElection()
	{
		return $this->election;
	}

}
