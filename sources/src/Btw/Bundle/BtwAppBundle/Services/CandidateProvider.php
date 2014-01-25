<?php

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\PersistenceBundle\Entity\Candidate;
use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Btw\Bundle\PersistenceBundle\Entity\ConstituencyCandidacy;
use Doctrine\ORM\EntityManager;

/**
 * Provides access to {@link Candidate} entities.
 */
class CandidateProvider extends AbstractProvider
{

	/**
	 * @param EntityManager $entityManager
	 */
	function __construct(EntityManager $entityManager)
	{
		parent::__construct($entityManager);
	}

	/**
	 * Returns a candidate identified by the given id.
	 *
	 * @param int $id The id of the candidate entity.
	 *
	 * @return Candidate
	 */
	public function byId($id)
	{
		$repository = $this->getRepository('Candidate');
		return $repository->find($id);
	}

	/**
	 * Returns a list of candidates running for a post in the given constituency.
	 *
	 * @param Constituency $constituency
	 *
	 * @return Candidate[]
	 */
	public function forConstituency(Constituency $constituency)
	{
		$repository = $this->getRepository('ConstituencyCandidacy');
		/** @var ConstituencyCandidacy[] $candidacies */
		$candidacies = $repository->findBy(array('constituency' => $constituency));

		$candidates = array();
		foreach ($candidacies as $candidacy) {
			$candidates[] = $candidacy->getCandidate();
		}

		return $candidates;
	}

}
