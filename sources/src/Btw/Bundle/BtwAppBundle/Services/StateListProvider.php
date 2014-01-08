<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 07.01.14
 * Time: 21:13
 */

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\PersistenceBundle\Entity\StateList;
use Btw\Bundle\PersistenceBundle\Entity\State;

class StateListProvider extends AbstractProvider
{

	/**
	 * @param State $state
	 *
	 * @return StateList[]
	 */
	public function forState(State $state)
	{
		$stateList = $this->getRepository('StateList')->findBy(array('state' => $state));
		return $stateList;
	}
} 