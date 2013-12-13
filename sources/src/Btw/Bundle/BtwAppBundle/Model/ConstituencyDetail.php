<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 13.12.13
 * Time: 17:33
 */

namespace Btw\Bundle\BtwAppBundle\Model;


class ConstituencyDetail {

	private $constituencyId;

	private $stateId;

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
	 * @param mixed $electives
	 */
	public function setElectives($electives)
	{
		$this->electives = $electives;
	}

	/**
	 * @return mixed
	 */
	public function getElectives()
	{
		return $this->electives;
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
	 * @param mixed $voters
	 */
	public function setVoters($voters)
	{
		$this->voters = $voters;
	}

	/**
	 * @return mixed
	 */
	public function getVoters()
	{
		return $this->voters;
	}

	private $name;

	private $electives;

	private $voters;
} 