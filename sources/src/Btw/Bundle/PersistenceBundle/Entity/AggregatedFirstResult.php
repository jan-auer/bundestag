<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contains the total number of votes for a candidate in a specific constituency. Both the candidate and the constituency
 * can be accessed via the {@link ConstituencyCandidacy} property. Values for this entity are automatically generated when
 * importing election results into the database using the importer script.
 *
 * @ORM\Table(name="aggregated_first_result")
 * @ORM\Entity
 */
class AggregatedFirstResult
{

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="aggregated_first_result_id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="aggregated_first_result_aggregated_first_result_id_seq", allocationSize=1, initialValue=1)
	 */
	private $id;

	/**
	 * @var ConstituencyCandidacy
	 *
	 * @ORM\ManyToOne(targetEntity="ConstituencyCandidacy")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="candidate_id", referencedColumnName="candidate_id")
	 * })
	 */
	private $constituencyCandidacy;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="count", type="integer", nullable=false)
	 */
	private $count;

	/**
	 * @param ConstituencyCandidacy $constituencyCandidacy
	 *
	 * @return AggregatedFirstResult
	 */
	public function setConstituencyCandidacy(ConstituencyCandidacy $constituencyCandidacy)
	{
		$this->constituencyCandidacy = $constituencyCandidacy;
		return $this;
	}

	/**
	 * @return ConstituencyCandidacy
	 */
	public function getConstituencyCandidacy()
	{
		return $this->constituencyCandidacy;
	}

	/**
	 * @param int $id
	 *
	 * @return AggregatedFirstResult
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
	 * @param int $count
	 *
	 * @return AggregatedFirstResult
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
