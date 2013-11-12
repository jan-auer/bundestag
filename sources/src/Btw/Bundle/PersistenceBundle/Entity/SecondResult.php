<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SecondResult
 *
 * @ORM\Table(name="second_result")
 * @ORM\Entity
 */
class SecondResult
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
     * @var StateList
     *
     * @ORM\ManyToOne(targetEntity="StateList")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="state_list_id", referencedColumnName="id")
     * })
     */
    private $stateList;

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
	 * @param ResultType $resultType
	 *
	 * @return SecondResult
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
	 * @return SecondResult
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
