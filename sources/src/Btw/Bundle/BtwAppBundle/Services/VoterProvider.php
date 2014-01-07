<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 07.01.14
 * Time: 16:31
 */

namespace Btw\Bundle\BtwAppBundle\Services;


class VoterProvider
	extends AbstractProvider{

	public function byHash($hash)
	{
		$voter = $this->em->getRepository('Btw\Bundle\PersistenceBundle\Entity\Voter')->findBy(array('hash' => $hash));

	}
} 