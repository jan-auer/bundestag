<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Site
 *
 * @ORM\Table(name="site")
 * @ORM\Entity
 */
class Site
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="site_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", nullable=true)
     */
    private $name;

    /**
     * @var Constituency
     *
     * @ORM\ManyToOne(targetEntity="Constituency")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="constituency_id", referencedColumnName="id")
     * })
     */
    private $constituency;

	/**
	 * @param Constituency $constituency
	 *
	 * @return Site
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

	/**
	 * @param int $id
	 *
	 * @return Site
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
	 * @param string $name
	 *
	 * @return Site
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

}
