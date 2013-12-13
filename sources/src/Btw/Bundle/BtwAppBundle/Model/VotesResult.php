<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 13.12.13
 * Time: 16:01
 */

namespace Btw\Bundle\BtwAppBundle\Model;


class VotesResult {

	private $stateId;

	private $constituencyId;

	private $partyId;

	private $votes;

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

	/**
	 * @param mixed $votes
	 */
	public function setVotes($votes)
	{
		$this->votes = $votes;
	}

	/**
	 * @return mixed
	 */
	public function getVotes()
	{
		return $this->votes;
	}
} 