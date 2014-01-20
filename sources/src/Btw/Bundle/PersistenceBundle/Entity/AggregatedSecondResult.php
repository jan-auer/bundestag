<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contains the total number of votes for a party in a specific constituency. The party can be accessed through the
 * {@link StateList} property. Values for this entity are automatically generated when importing election results into
 * the database using the importer script.
 *
 * @ORM\Table(name="aggregated_second_result")
 * @ORM\Entity
 */
class AggregatedSecondResult
{

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="aggregated_second_result_id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="aggregated_second_result_aggregated_second_result_id_seq", allocationSize=1, initialValue=1)
	 */
	private $id;

	/**
	 * @var StateList
	 *
	 * @ORM\ManyToOne(targetEntity="StateList")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="state_list_id", referencedColumnName="state_list_id")
	 * })
	 */
	private $stateList;

	/**
	 * @var \Btw\Bundle\PersistenceBundle\Entity\Constituency
	 *
	 * @ORM\ManyToOne(targetEntity="Btw\Bundle\PersistenceBundle\Entity\Constituency")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="constituency_id", referencedColumnName="constituency_id")
	 * })
	 */
	private $constituency;

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

	/**
	 * @param Constituency $constituency
	 *
	 * @return $this
	 */
	public function setConstituency(Constituency $constituency)
	{
		$this->constituency = $constituency;
		return $this;
	}

	/**
	 * @return Constituency
	 */
	public function getConstituency()
	{
		return $this->constituency;
	}

}
