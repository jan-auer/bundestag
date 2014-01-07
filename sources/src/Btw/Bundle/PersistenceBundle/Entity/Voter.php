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
     * @ORM\Column(name="voter_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="voter_voter_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="identityNumber", type="integer", nullable=false)
     */
    private $identityNumber;

    /**
     * @var String
     *
     * @ORM\Column(name="hash", type="string", nullable=false)
     */
    private $hash;

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
	 * @param String $hash
	 */
	public function setHash($hash)
	{
		$this->hash = $hash;
	}

	/**
	 * @return String
	 */
	public function getHash()
	{
		return $this->hash;
	}

	/**
	 * @param int $identityNumber
	 */
	public function setIdentityNumber($identityNumber)
	{
		$this->identityNumber = $identityNumber;
	}

	/**
	 * @return int
	 */
	public function getIdentityNumber()
	{
		return $this->identityNumber;
	}


}
