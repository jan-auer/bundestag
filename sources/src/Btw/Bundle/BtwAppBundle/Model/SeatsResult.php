<?php

namespace Btw\Bundle\BtwAppBundle\Model;

class SeatsResult
	implements ModelInterface
{

	/** @var  int */
	private $state;
	/** @var  int */
	private $party;
	/** @var  int */
	private $seats;
	/** @var  int */
	private $overhead;

	/**
	 * @inheritdoc
	 */
	public static function fromArray(array &$data)
	{
		$model = new SeatsResult();
		$model->setState($data['state']);
		$model->setParty($data['party']);
		$model->setSeats($data['seats']);
		$model->setOverhead($data['overhead']);
		return $model;
	}

	/**
	 * @inheritdoc
	 */
	public function toArray()
	{
		return array(
			'state'    => $this->getState(),
			'party'    => $this->getParty(),
			'seats'    => $this->getSeats(),
			'overhead' => $this->getOverhead(),
		);
	}

	/**
	 * @param mixed $overhead
	 *
	 * @return SeatsResult
	 */
	public function setOverhead($overhead)
	{
		$this->overhead = $overhead;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getOverhead()
	{
		return $this->overhead;
	}

	/**
	 * @param mixed $partyId
	 *
	 * @return SeatsResult
	 */
	public function setParty($partyId)
	{
		$this->party = $partyId;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getParty()
	{
		return $this->party;
	}

	/**
	 * @param mixed $seats
	 *
	 * @return SeatsResult
	 */
	public function setSeats($seats)
	{
		$this->seats = $seats;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getSeats()
	{
		return $this->seats;
	}

	/**
	 * @param mixed $stateId
	 *
	 * @return SeatsResult
	 */
	public function setState($stateId)
	{
		$this->state = $stateId;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getState()
	{
		return $this->state;
	}

}
