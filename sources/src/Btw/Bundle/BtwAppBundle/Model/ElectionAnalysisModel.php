<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 07/12/13
 * Time: 18:07
 */

namespace Btw\Bundle\BtwAppBundle\Model;


class ElectionAnalysisModel
{

	private $state;

	private $constituency;

	/**
	 * @param mixed $constituency
	 * @return ElectionAnalysisModel
	 */
	public function setConstituency($constituency)
	{
		$this->constituency = $constituency;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getConstituency()
	{
		return $this->constituency;
	}

	/**
	 * @param mixed $state
	 * @return ElectionAnalysisModel
	 */
	public function setState($state)
	{
		$this->state = $state;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getState()
	{
		return $this->state;
	}

}