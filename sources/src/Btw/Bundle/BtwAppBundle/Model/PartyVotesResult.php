<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 11.12.13
 * Time: 20:10
 */

namespace Btw\Bundle\BtwAppBundle\Model;


class PartyVotesResult
{

	private $abbreviation;

	private $color;

	private $votes;

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