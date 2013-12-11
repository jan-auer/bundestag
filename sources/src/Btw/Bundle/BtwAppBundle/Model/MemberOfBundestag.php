<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 11.12.13
 * Time: 19:10
 */

namespace Btw\Bundle\BtwAppBundle\Model;


use Btw\Bundle\PersistenceBundle\Entity\Candidate;

class MemberOfBundestag {

	/**
	 * @var Candidate
	 */
	private $name;

	/**
	 * @param mixed $isDirect
	 */
	public function setIsDirect($isDirect)
	{
		$this->isDirect = $isDirect;
	}

	/**
	 * @return mixed
	 */
	public function getIsDirect()
	{
		return $this->isDirect;
	}

	/**
	 * @param \Btw\Bundle\PersistenceBundle\Entity\Candidate $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return \Btw\Bundle\PersistenceBundle\Entity\Candidate
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @var
	 */
	private $isDirect;
} 