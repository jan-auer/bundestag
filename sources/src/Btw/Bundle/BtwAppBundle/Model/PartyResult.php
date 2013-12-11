<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 11.12.13
 * Time: 21:41
 */

namespace Btw\Bundle\BtwAppBundle\Model;


class PartyResult
{

	private $partyAbbreviation;

	private $partyFullName;

	private $color;

	/**
	 * @param mixed $color
	 */
	public function setColor($color)
	{
		$this->color = $color;
	}

	/**
	 * @return mixed
	 */
	public function getColor()
	{
		return $this->color;
	}

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
	 * @param mixed $partyAbbreviation
	 */
	public function setPartyAbbreviation($partyAbbreviation)
	{
		$this->partyAbbreviation = $partyAbbreviation;
	}

	/**
	 * @return mixed
	 */
	public function getPartyAbbreviation()
	{
		return $this->partyAbbreviation;
	}

	/**
	 * @param mixed $partyFullName
	 */
	public function setPartyFullName($partyFullName)
	{
		$this->partyFullName = $partyFullName;
	}

	/**
	 * @return mixed
	 */
	public function getPartyFullName()
	{
		return $this->partyFullName;
	}

	/**
	 * @param mixed $percentage
	 */
	public function setPercentage($percentage)
	{
		$this->percentage = $percentage;
	}

	/**
	 * @return mixed
	 */
	public function getPercentage()
	{
		return $this->percentage;
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

	private $seats;

	private $votes;

	private $percentage;

	private $overhead;
} 