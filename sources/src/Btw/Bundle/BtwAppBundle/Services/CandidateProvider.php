<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 07.01.14
 * Time: 16:54
 */

namespace Btw\Bundle\BtwAppBundle\Services;


use Btw\Bundle\PersistenceBundle\Entity\Candidate;
use Btw\Bundle\PersistenceBundle\Entity\Constituency;

class CandidateProvider extends AbstractProvider
{

	/**
	 * @param Constituency $constituency
	 *
	 * @return Candidate[]
	 */
	public function forConstituency(Constituency $constituency)
	{
		$constituency->setId(2);
		$candidacies = $this->getRepository('ConstituencyCandidacy')->findBy(array('constituency' => $constituency));

		$candidates = array();
		foreach ($candidacies as $candidacy) {
			$candidates[] = $candidacy->getCandidate();
		}
		return $candidates;
	}

}
