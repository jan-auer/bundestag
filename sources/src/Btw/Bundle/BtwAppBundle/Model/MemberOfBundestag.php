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

	private $name;

	private $partyAbbreviation;

	private $isDirect;

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
	 * @param mixed $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param mixed $partyAbbreviation
	 */
	public function setPartyAbbreviation($partyAbbreviation)
	{
		$this->partyAbbreviation = $partyAbbreviation;
	}

	/**
	 * @return mixed
	 */
	public function getPartyAbbreviation()
	{
		return $this->partyAbbreviation;
	}
} 