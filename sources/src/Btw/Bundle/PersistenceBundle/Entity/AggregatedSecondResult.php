<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AggregatedSecondResult
 *
 * @ORM\Table(name="aggregated_second_result")
 * @ORM\Entity
 */
class AggregatedSecondResult
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="second_result_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var StateList
     *
     * @ORM\ManyToOne(targetEntity="StateList")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="state_list_id", referencedColumnName="id")
     * })
     */
    private $stateList;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="count", type="integer", nullable=false)
	 */
	private $count;

	/**
	 * @param int $id
	 *
	 * @return AggregatedSecondResult
	 */
	public function setId($id)
	{
		$this->id = $id;
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
	 * @param StateList $stateList
	 *
	 * @return AggregatedSecondResult
	 */
	public function setStateList(StateList $stateList)
	{
		$this->stateList = $stateList;
		return $this;
	}

	/**
	 * @return StateList
	 */
	public function getStateList()
	{
		return $this->stateList;
	}

	/**
	 * @param int $count
	 *
	 * @return AggregatedSecondResult
	 */
	public function setCount($count)
	{
		$this->count = $count;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCount()
	{
		return $this->count;
	}

}
