<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This is one single vote for a state list (see {@link StateList}). Usually, these votes are aggregated and accessed
 * via {@link AggregatedSecondResult}. Even though it is not relevant for the election and seat distribution process,
 * the constituency of this vote is also saved for analytical aspects.
 *
 * @ORM\Table(name="second_result")
 * @ORM\Entity
 */
class SecondResult
{

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="second_result_id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="second_result_second_result_id_seq", allocationSize=1, initialValue=1)
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
	 * @var Constituency
	 *
	 * @ORM\ManyToOne(targetEntity="Btw\Bundle\PersistenceBundle\Entity\Constituency")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="constituency_id", referencedColumnName="constituency_id")
	 * })
	 */
	private $constituency;

	/**
	 * @param int $id
	 *
	 * @return SecondResult
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
	 * @return SecondResult
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

}
