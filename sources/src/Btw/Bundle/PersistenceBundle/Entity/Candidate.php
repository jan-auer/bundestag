<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Candidate
 *
 * @ORM\Table(name="candidate")
 * @ORM\Entity
 */
class Candidate
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="candidate_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", nullable=false)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date", nullable=true)
     */
    private $birthday;

	/**
	 * @var Party
	 *
	 * @ORM\ManyToOne(targetEntity="Party")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="party_id", referencedColumnName="id")
	 * })
	 */
    private $party;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->stateList = new ArrayCollection();
    }

	/**
	 * @param \DateTime $birthday
	 *
	 * @return Candidate
	 */
	public function setBirthday(\DateTime $birthday)
	{
		$this->birthday = $birthday;
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getBirthday()
	{
		return $this->birthday;
	}

	/**
	 * @param int $id
	 *
	 * @return Candidate
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
	 * @return Candidate
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
	 * @param Party $party
	 *
	 * @return Candidate
	 */
	public function setParty(Party $party)
	{
		$this->party = $party;
		return $this;
	}

	/**
	 * @return Party
	 */
	public function getParty()
	{
		return $this->party;
	}

}
