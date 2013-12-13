<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 13.12.13
 * Time: 15:53
 */

namespace Btw\Bundle\BtwAppBundle\Model;


class SeatsResult {

	private $stateId;

	private $partyId;

	private $seats;

	private $overhead;

	/**
	 * @param mixed $overhead
	 */
	public function setOverhead($overhead)
	{
		$this->overhead = $overhead;
	}

	/**
	 * @return mixed
	 */
	public function getOverhead()
	{
		return $this->overhead;
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
	 * @param mixed $seats
	 */
	public function setSeats($seats)
	{
		$this->seats = $seats;
	}

	/**
	 * @return mixed
	 */
	public function getSeats()
	{
		return $this->seats;
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