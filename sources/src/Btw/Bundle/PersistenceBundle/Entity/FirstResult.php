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
	 * @var ResultType
	 *
	 * @ORM\ManyToOne(targetEntity="ResultType")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="result_type_id", referencedColumnName="id")
	 * })
	 */
	private $resultType;

	/**
	 * @var Site
	 *
	 * @ORM\ManyToOne(targetEntity="Site")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="site_id", referencedColumnName="id")
	 * })
	 */
	private $site;

	/**
	 * @var Candidate
	 *
	 * @ORM\ManyToOne(targetEntity="Candidate")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="candidate_id", referencedColumnName="id")
	 * })
	 */
	private $candidate;

	/**
	 * @param Candidate $candidate
	 *
	 * @return FirstResult
	 */
	public function setCandidate(Candidate $candidate)
	{
		$this->candidate = $candidate;
		return $this;
	}

	/**
	 * @return Candidate
	 */
	public function getCandidate()
	{
		return $this->candidate;
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

	/**
	 * @param ResultType $resultType
	 *
	 * @return FirstResult
	 */
	public function setResultType(ResultType $resultType)
	{
		$this->resultType = $resultType;
		return $this;
	}

	/**
	 * @return ResultType
	 */
	public function getResultType()
	{
		return $this->resultType;
	}

	/**
	 * @param Site $site
	 *
	 * @return FirstResult
	 */
	public function setSite(Site $site)
	{
		$this->site = $site;
		return $this;
	}

	/**
	 * @return Site
	 */
	public function getSite()
	{
		return $this->site;
	}

}
