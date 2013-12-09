<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 09.12.13
 * Time: 17:50
 */

namespace Btw\Bundle\BtwAppBundle\Model;


class LocationDetailsModel {

	private $name;

	private $population;

	private $participation;

	/**
	 * @param mixed $membersOfBundestag
	 */
	public function setMembersOfBundestag($membersOfBundestag)
	{
		$this->membersOfBundestag = $membersOfBundestag;
	}

	/**
	 * @return mixed
	 */
	public function getMembersOfBundestag()
	{
		return $this->membersOfBundestag;
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

	private $membersOfBundestag;
} 