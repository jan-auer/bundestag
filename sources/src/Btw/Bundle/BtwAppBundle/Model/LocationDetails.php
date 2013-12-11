<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 11.12.13
 * Time: 19:08
 */

namespace Btw\Bundle\BtwAppBundle\Model;


class LocationDetails
{

	/**
	 * @var
	 */
	private $population;

	/**
	 * @param mixed $participation
	 */
	public function setParticipation($participation)
	{
		$this->participation = $participation;
	}

	/**
	 * @return mixed
	 */
	public function getParticipation()
	{
		return $this->participation;
	}

	/**
	 * @param mixed $population
	 */
	public function setPopulation($population)
	{
		$this->population = $population;
	}

	/**
	 * @return mixed
	 */
	public function getPopulation()
	{
		return $this->population;
	}

	/**
	 * @var
	 */
	private $participation;

} 