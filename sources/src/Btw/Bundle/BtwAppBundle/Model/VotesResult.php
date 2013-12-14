<?php

namespace Btw\Bundle\BtwAppBundle\Model;

class VotesResult
	implements ModelInterface
{

	/** @var  int */
	private $state;
	/** @var  int */
	private $constituency;
	/** @var  int */
	private $party;
	/** @var  int */
	private $votes;
	/** @var  int */
	private $votesPrev;

	/**
	 * @param array $data
	 *
	 * @return VotesResult
	 */
	public static function fromArray(array &$data)
	{
		$model = new VotesResult();
		$model->setState($data['state']);
		$model->setConstituency($data['constituency']);
		$model->setParty($data['party']);
		$model->setVotes($data['votes']);
		if (array_key_exists('votes_prev', $data)) {
			$model->setVotesPrev($data['votes_prev']);
		} else {
			$model->setVotesPrev(0);
		}
		return $model;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return array(
			'state' => $this->getState(),
			'constituency' => $this->getConstituency(),
			'party' => $this->getParty(),
			'votes' => $this->getVotes(),
			'votesPrev' => $this->getVotesPrev(),
		);
	}

	/**
	 * @param mixed $votesPrev
	 */
	public function setVotesPrev($votesPrev)
	{
		$this->votesPrev = $votesPrev;
	}

	/**
	 * @return mixed
	 */
	public function getVotesPrev()
	{
		return $this->votesPrev;
	}

	/**
	 * @param int $constituency
	 *
	 * @return VotesResult
	 */
	public function setConstituency($constituency)
	{
		$this->constituency = $constituency;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getConstituency()
	{
		return $this->constituency;
	}

	/**
	 * @param int $party
	 *
	 * @return VotesResult
	 */
	public function setParty($party)
	{
		$this->party = $party;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getParty()
	{
		return $this->party;
	}

	/**
	 * @param int $state
	 *
	 * @return VotesResult
	 */
	public function setState($state)
	{
		$this->state = $state;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getState()
	{
		return $this->state;
	}

	/**
	 * @param int $votes
	 *
	 * @return VotesResult
	 */
	public function setVotes($votes)
	{
		$this->votes = $votes;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getVotes()
	{
		return $this->votes;
	}

}
