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
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="party_id_seq", allocationSize=1, initialValue=1)
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
     * @var \DateTime
     *
     * @ORM\Column(name="formation_date", type="date", nullable=true)
     */
    private $formationDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="members", type="integer", nullable=true)
     */
    private $members;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="text", nullable=true)
     */
    private $color;

    /**
     * @var boolean
     *
     * @ORM\Column(name="minority_representation", type="boolean", nullable=false)
     */
    private $minorityRepresentation;

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
	 * @param \DateTime $formationDate
	 *
	 * @return Party
	 */
	public function setFormationDate(\DateTime $formationDate)
	{
		$this->formationDate = $formationDate;
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getFormationDate()
	{
		return $this->formationDate;
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
	 * @param boolean $minorityRepresentation
	 *
	 * @return Party
	 */
	public function setMinorityRepresentation($minorityRepresentation)
	{
		$this->minorityRepresentation = $minorityRepresentation;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isMinorityRepresentation()
	{
		return $this->minorityRepresentation;
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


}
