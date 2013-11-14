<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * State
 *
 * @ORM\Table(name="state")
 * @ORM\Entity
 */
class State
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="state_id_seq", allocationSize=1, initialValue=1)
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
     *   @ORM\JoinColumn(name="election_id", referencedColumnName="id")
     * })
     */
    private $election;

	/**
	 * @param Election $election
	 *
	 * @return State
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
	 * @param int $id
	 *
	 * @return State
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
	 * @return State
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
	 * @param int $population
	 *
	 * @return State
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
	 * @param int $number
	 */
	public function setNumber($number)
	{
		$this->number = $number;
	}

	/**
	 * @return int
	 */
	public function getNumber()
	{
		return $this->number;
	}

}
