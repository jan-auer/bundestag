<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Constituency
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
	 * @ORM\Column(name="population", type="integer", nullable=false)
	 */
	private $population;

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


}
