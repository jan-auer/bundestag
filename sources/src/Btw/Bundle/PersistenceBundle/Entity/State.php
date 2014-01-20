<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Holds information about a state.
 * A new entity is created for each election, however, states can be compared via their names.
 *
 * @ORM\Table(name="state")
 * @ORM\Entity
 */
class State
{

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="state_id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="state_state_id_seq", allocationSize=1, initialValue=1)
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
	 * @ORM\Column(name="name", type="text", nullable=false)
	 */
	private $name;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="population", type="integer", nullable=false)
	 */
	private $population;

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
	 * @var Constituency[]
	 *
	 * @ORM\OneToMany(targetEntity="Constituency", mappedBy="state")
	 * @ORM\OrderBy({"name" = "ASC"})
	 */
	private $constituencies;

	/**
	 * @param Election $election
	 *
	 * @return $this
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

	/**
	 * @param $id
	 *
	 * @return $this
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
	 * @param $name
	 *
	 * @return $this
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
	 * @param $population
	 *
	 * @return $this
	 */
	public function setPopulation($population)
	{
		$this->population = $population;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getPopulation()
	{
		return $this->population;
	}

	/**
	 * @param $number
	 *
	 * @return $this
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
	 * @param Constituency[] $constituencies
	 *
	 * @return $this
	 */
	public function setConstituencies($constituencies)
	{
		$this->constituencies = $constituencies;
		return $this;
	}

	/**
	 * @return Constituency[]
	 */
	public function getConstituencies()
	{
		return $this->constituencies;
	}

	function __toString()
	{
		return $this->getName();
	}

}
