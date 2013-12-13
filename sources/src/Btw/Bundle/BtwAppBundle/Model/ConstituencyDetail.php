<?php

namespace Btw\Bundle\BtwAppBundle\Model;

class ConstituencyDetail
	implements ModelInterface
{

	/** @var  int */
	private $id;
	/** @var  int */
	private $stateId;
	/** @var  string */
	private $name;
	/** @var  int */
	private $electives;
	/** @var  int */
	private $voters;
	/** @var  int */
	private $votersPrev;

	/**
	 * @param array $data
	 *
	 * @return ConstituencyDetail
	 */
	public static function fromArray(array &$data)
	{
		$model = new ConstituencyDetail();
		$model->setId($data['id']);
		$model->setStateId($data['state']);
		$model->setName($data['name']);
		$model->setElectives($data['electives']);
		$model->setVoters($data['voters']);
		$model->setVotersPrev($data['voters_prev']);
		return $model;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return array(
			'id'         => $this->getId(),
			'state'      => $this->getStateId(),
			'name'       => $this->getName(),
			'electives'  => $this->getElectives(),
			'voters'     => $this->getVoters(),
			'votersPrev' => $this->getVotersPrev(),
		);
	}

	/**
	 * @param int $constituencyId
	 *
	 * @return ConstituencyDetail
	 */
	public function setId($constituencyId)
	{
		$this->id = $constituencyId;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param int $electives
	 *
	 * @return ConstituencyDetail
	 */
	public function setElectives($electives)
	{
		$this->electives = $electives;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getElectives()
	{
		return $this->electives;
	}

	/**
	 * @param string $name
	 *
	 * @return ConstituencyDetail
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param int $stateId
	 *
	 * @return ConstituencyDetail
	 */
	public function setStateId($stateId)
	{
		$this->stateId = $stateId;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getStateId()
	{
		return $this->stateId;
	}

	/**
	 * @param int $voters
	 *
	 * @return ConstituencyDetail
	 */
	public function setVoters($voters)
	{
		$this->voters = $voters;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getVoters()
	{
		return $this->voters;
	}

	/**
	 * @param int $votersPrev
	 *
	 * @return ConstituencyDetail
	 */
	public function setVotersPrev($votersPrev)
	{
		$this->votersPrev = $votersPrev;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getVotersPrev()
	{
		return $this->votersPrev;
	}

}
