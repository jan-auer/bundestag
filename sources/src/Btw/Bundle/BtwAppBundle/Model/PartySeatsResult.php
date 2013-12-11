<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 11.12.13
 * Time: 20:01
 */

namespace Btw\Bundle\BtwAppBundle\Model;


class PartySeatsResult {

	private $abbreviation;

	private $color;

	private $seats;

	/**
	 * @param mixed $abbreviation
	 */
	public function setAbbreviation($abbreviation)
	{
		$this->abbreviation = $abbreviation;
	}

	/**
	 * @return mixed
	 */
	public function getAbbreviation()
	{
		return $this->abbreviation;
	}

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
} 