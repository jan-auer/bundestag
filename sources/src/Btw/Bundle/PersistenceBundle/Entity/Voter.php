<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contains information about a voter. Voters have to register for each election and are then given a one time hash
 * to authenticate with the voting machine. In order to identify voters, they also have to deposit their identity card
 * numbers, which are unique among the German people.
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
	 * @ORM\Column(name="identity_number", type="integer", nullable=false)
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
	 * @var Constituency
	 *
	 * @ORM\ManyToOne(targetEntity="Constituency")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="constituency_id", referencedColumnName="constituency_id")
	 * })
	 */
	private $constituency;

	/**
	 * @var boolean
	 * @ORM\Column(name="voted", type="boolean")
	 */
	private $voted = false;

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
	 *
	 * @return Voter
	 */
	public function setHash($hash)
	{
		$this->hash = $hash;
		return $this;
	}

	/**
	 * @return String
	 */
	public function getHash()
	{
		return $this->hash;
	}

	/**
	 * @param $identityNumber
	 *
	 * @return Voter
	 */
	public function setIdentityNumber($identityNumber)
	{
		$this->identityNumber = $identityNumber;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getIdentityNumber()
	{
		return $this->identityNumber;
	}

	/**
	 * @param Constituency $constituency
	 *
	 * @return Voter
	 */
	public function setConstituency($constituency)
	{
		$this->constituency = $constituency;
		return $this;
	}

	/**
	 * @return Constituency
	 */
	public function getConstituency()
	{
		return $this->constituency;
	}

	/**
	 * @param boolean $voted
	 *
	 * @return Voter
	 */
	public function setVoted($voted)
	{
		$this->voted = $voted;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getVoted()
	{
		return $this->voted;
	}

}
