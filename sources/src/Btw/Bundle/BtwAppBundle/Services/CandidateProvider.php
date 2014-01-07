<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 07.01.14
 * Time: 16:54
 */

namespace Btw\Bundle\BtwAppBundle\Services;


use Btw\Bundle\PersistenceBundle\Entity\Candidate;

class CandidateProvider {

	/**
	 * @param Election $election
	 * @param Constituency $constituency
	 *
	 * @return Candidate[]
	 */
	public function forConstituency(Constituency $constituency)
	{
		$constituencyId = $constituency->getId();
		$candidate = $this->em->getRepository('Btw\Bundle\PersistenceBundle\Entity\Candidate')->findBy(array('constituency_id' => $constituencyId));
		if (is_array($candidate) && count($candidate)) {
			return $candidate[0];
		}
		return null;
	}
} 