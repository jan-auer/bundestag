<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 07.01.14
 * Time: 21:13
 */

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\PersistenceBundle\Entity\StateList;

class StateListProvider
{

	/**
	 * @param State $state
	 *
	 * @return StateList[]
	 */
	public function forState(State $state)
	{
		$stateId = $state->getId();
		$stateList = $this->em->getRepository('Btw\Bundle\PersistenceBundle\Entity\StateList')->findBy(array('state_id' => $stateId));
		return $stateList;
	}
} 