<?php

namespace Btw\Bundle\PersistenceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * StateList
 *
 * @ORM\Table(name="state_list")
 * @ORM\Entity
 */
class StateList
{
    /**
     * @var integer
     *
     * @ORM\Column(name="state_list_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="state_list_state_list_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var State
     *
     * @ORM\ManyToOne(targetEntity="State")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="state_id", referencedColumnName="state_id")
     * })
     */
    private $state;

    /**
     * @var Party
     *
     * @ORM\ManyToOne(targetEntity="Party")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="party_id", referencedColumnName="party_id")
     * })
     */
    private $party;

	/**
	 * @param int $id
	 *
	 * @return StateList
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
	 * @param Party $party
	 *
	 * @return StateList
	 */
	public function setParty(Party $party)
	{
		$this->party = $party;
		return $this;
	}

	/**
	 * @return Party
	 */
	public function getParty()
	{
		return $this->party;
	}

	/**
	 * @param State $state
	 *
	 * @return StateList
	 */
	public function setState(State $state)
	{
		$this->state = $state;
		return $this;
	}

	/**
	 * @return State
	 */
	public function getState()
	{
		return $this->state;
	}

}
