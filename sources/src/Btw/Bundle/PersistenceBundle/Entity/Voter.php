<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Voter
 *
 * @ORM\Table(name="voter")
 * @ORM\Entity
 */
class Voter
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="voter_id_seq", allocationSize=1, initialValue=1)
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
     * @ORM\Column(name="birthday", type="date", nullable=false)
     */
    private $birthday;

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
	 * @param \DateTime $birthday
	 *
	 * @return Voter
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
	 * @param Election $election
	 *
	 * @return Voter
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
	 * @return Voter
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
	 * @return Voter
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
