<?php

namespace Btw\Bundle\BtwAppBundle\Model;

class PartyResult
	implements ModelInterface
{

	/** @var  string */
	private $abbr;
	/** @var  string */
	private $name;
	/** @var  string */
	private $color;
	/** @var  int */
	private $seats;
	/** @var  int */
	private $overhead;

	/**
	 * @param array $data
	 *
	 * @return PartyResult
	 */
	public static function fromArray(array &$data)
	{
		$model = new PartyResult();
		$model->setAbbr($data['abbr']);
		$model->setName($data['name']);
		$model->setColor($data['color']);
		$model->setSeats($data['seats']);
		$model->setOverhead($data['overhead']);
		return $model;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return array(
			'abbr'     => $this->getAbbr(),
			'name'     => $this->getName(),
			'color'    => $this->getColor(),
			'seats'    => $this->getSeats(),
			'overhead' => $this->getOverhead(),
		);
	}

	/**
	 * @param string $abbr
	 *
	 * @return PartyResult
	 */
	public function setAbbr($abbr)
	{
		$this->abbr = $abbr;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAbbr()
	{
		return $this->abbr;
	}

	/**
	 * @param string $color
	 *
	 * @return PartyResult
	 */
	public function setColor($color)
	{
		$this->color = $color;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getColor()
	{
		return $this->color;
	}

	/**
	 * @param string $name
	 *
	 * @return PartyResult
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
	 * @param int $overhead
	 *
	 * @return PartyResult
	 */
	public function setOverhead($overhead)
	{
		$this->overhead = $overhead;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getOverhead()
	{
		return $this->overhead;
	}

	/**
	 * @param int $seats
	 *
	 * @return PartyResult
	 */
	public function setSeats($seats)
	{
		$this->seats = $seats;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSeats()
	{
		return $this->seats;
	}

}
