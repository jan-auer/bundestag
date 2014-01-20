<?php

namespace Btw\Bundle\BtwAppBundle\Model;

class MemberOfBundestag
	implements ModelInterface
{
	/** @var  string */
	private $name;
	/** @var int */
	private $candidate;
	/** @var  int */
	private $state;
	/** @var  int */
	private $constituency;
	/** @var  int */
	private $party;

	/**
	 * @inheritdoc
	 */
	public static function fromArray(array &$data)
	{
		$model = new MemberOfBundestag();
		$model->setName($data['name']);
		$model->setCandidate($data['candidate']);
		$model->setState($data['state']);
		$model->setConstituency($data['constituency']);
		$model->setParty($data['party']);
		return $model;
	}

	/**
	 * @inheritdoc
	 */
	public function toArray()
	{
		return array(
			'name'         => $this->getName(),
			'candidate'    => $this->getCandidate(),
			'state'        => $this->getState(),
			'constituency' => $this->getConstituency(),
			'party'        => $this->getParty(),
		);
	}

	/**
	 * @param int $candidate
	 *
	 * @return MemberOfBundestag
	 */
	public function setCandidate($candidate)
	{
		$this->candidate = $candidate;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCandidate()
	{
		return $this->candidate;
	}

	/**
	 * @param int $constituency
	 *
	 * @return MemberOfBundestag
	 */
	public function setConstituency($constituency)
	{
		$this->constituency = $constituency;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getConstituency()
	{
		return $this->constituency;
	}

	/**
	 * @param string $name
	 *
	 * @return MemberOfBundestag
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
	 * @param int $party
	 *
	 * @return MemberOfBundestag
	 */
	public function setParty($party)
	{
		$this->party = $party;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getParty()
	{
		return $this->party;
	}

	/**
	 * @param int $state
	 *
	 * @return MemberOfBundestag
	 */
	public function setState($state)
	{
		$this->state = $state;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getState()
	{
		return $this->state;
	}

}
