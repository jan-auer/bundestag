<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Party
 *
 * @ORM\Table(name="party")
 * @ORM\Entity
 */
class Party
{
    /**
     * @var integer
     *
     * @ORM\Column(name="party_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="party_party_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="abbreviation", type="text", nullable=true)
     */
    private $abbreviation;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="text", nullable=true)
     */
    private $color;

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
	 * @param string $abbreviation
	 *
	 * @return Party
	 */
	public function setAbbreviation($abbreviation)
	{
		$this->abbreviation = $abbreviation;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAbbreviation()
	{
		return $this->abbreviation;
	}

	/**
	 * @param string $color
	 *
	 * @return Party
	 */
	public function setColor($color)
	{
		$this->color = $color;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getColor()
	{
		return $this->color;
	}

	/**
	 * @param int $id
	 *
	 * @return Party
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
	 * @param int $members
	 *
	 * @return Party
	 */
	public function setMembers($members)
	{
		$this->members = $members;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getMembers()
	{
		return $this->members;
	}

	/**
	 * @param string $name
	 *
	 * @return Party
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
	 * @param Election $election
	 *
	 * @return Party
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
