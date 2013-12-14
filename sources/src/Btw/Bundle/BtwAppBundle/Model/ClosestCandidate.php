<?php

namespace Btw\Bundle\BtwAppBundle\Model;

class ClosestCandidate
	implements ModelInterface
{

	/** @var  string */
	private $name;
	/** @var  string */
	private $constituency;
	/** @var  string */
	private $type;

	/**
	 * @param array $data
	 *
	 * @return ClosestCandidate
	 */
	public static function fromArray(array &$data)
	{
		$model = new ClosestCandidate();
		$model->setName($data['name']);
		$model->setConstituency($data['constituency']);
		$model->setType($data['type']);
		return $model;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return array(
			'name'         => $this->getName(),
			'constituency' => $this->getConstituency(),
			'type'         => $this->getType(),
		);
	}

	/**
	 * @param string $constituencyName
	 *
	 * @return ClosestCandidate
	 */
	public function setConstituency($constituencyName)
	{
		$this->constituency = $constituencyName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getConstituency()
	{
		return $this->constituency;
	}

	/**
	 * @param string $name
	 *
	 * @return ClosestCandidate
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
	 * @param string $type
	 *
	 * @return ClosestCandidate
	 */
	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

}
