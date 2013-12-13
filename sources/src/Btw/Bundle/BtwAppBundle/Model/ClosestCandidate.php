<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 13.12.13
 * Time: 21:13
 */

namespace Btw\Bundle\BtwAppBundle\Model;


class ClosestCandidate {

	private $name;

	private $constituencyName;

	private $type;

	/**
	 * @param mixed $constituencyName
	 */
	public function setConstituencyName($constituencyName)
	{
		$this->constituencyName = $constituencyName;
	}

	/**
	 * @return mixed
	 */
	public function getConstituencyName()
	{
		return $this->constituencyName;
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
	 * @param mixed $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return mixed
	 */
	public function getType()
	{
		return $this->type;
	}
} 