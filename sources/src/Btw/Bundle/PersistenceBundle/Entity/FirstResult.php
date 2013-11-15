<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FirstResult
 *
 * @ORM\Table(name="first_result")
 * @ORM\Entity
 */
class FirstResult
{

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="first_result_id_seq", allocationSize=1, initialValue=1)
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
	 * @param ConstituencyCandidacy $constituencyCandidacy
	 *
	 * @return FirstResult
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
	 * @return FirstResult
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

}
