<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 11.12.13
 * Time: 19:10
 */

namespace Btw\Bundle\BtwAppBundle\Model;


use Btw\Bundle\PersistenceBundle\Entity\Candidate;

class MemberOfBundestag {

	private $name;

	private $candidateId;

	private $stateId;

	private $constituencyId;

	private $partyId;

	/**
	 * @param mixed $candidateId
	 */
	public function setCandidateId($candidateId)
	{
		$this->candidateId = $candidateId;
	}

	/**
	 * @return mixed
	 */
	public function getCandidateId()
	{
		return $this->candidateId;
	}

	/**
	 * @param mixed $constituencyId
	 */
	public function setConstituencyId($constituencyId)
	{
		$this->constituencyId = $constituencyId;
	}

	/**
	 * @return mixed
	 */
	public function getConstituencyId()
	{
		return $this->constituencyId;
	}

	/**
	 * @param mixed $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param mixed $partyId
	 */
	public function setPartyId($partyId)
	{
		$this->partyId = $partyId;
	}

	/**
	 * @return mixed
	 */
	public function getPartyId()
	{
		return $this->partyId;
	}

	/**
	 * @param mixed $stateId
	 */
	public function setStateId($stateId)
	{
		$this->stateId = $stateId;
	}

	/**
	 * @return mixed
	 */
	public function getStateId()
	{
		return $this->stateId;
	}


} 