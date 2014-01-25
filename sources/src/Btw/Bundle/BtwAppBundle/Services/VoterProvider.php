<?php

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Btw\Bundle\PersistenceBundle\Entity\FirstResult;
use Btw\Bundle\PersistenceBundle\Entity\Voter;
use Doctrine\DBAL\DBALException;

class VoterProvider extends AbstractProvider
{

	/**
	 * Value for delimiting identity number of voter and election id in the hash.
	 */
	const DELIMITER_HASH = "|";

	/**
	 * Returns a voter identified by the given hash.
	 *
	 * @param string $hash A secret token handed to the voter.
	 *
	 * @return Voter
	 */
	public function byHash($hash)
	{
		$voter = $this->getRepository('Voter')->findBy(array('hash' => $hash));
		return is_array($voter) && count($voter) ? $voter[0] : null;
	}

	/**
	 * Creates a new voter entity.
	 *
	 * @param int          $identityNumber The identity card number of the voter.
	 * @param Constituency $constituency   The constituency, this voter is registered in.
	 *
	 * @return String The voter hash.
	 */
	public function createVoter($identityNumber, Constituency $constituency)
	{
		$hash = md5($identityNumber . self::DELIMITER_HASH . $constituency->getElection()->getId());

		$voter = new Voter();
		$voter->setConstituency($constituency);
		$voter->setHash($hash);
		$voter->setIdentityNumber($identityNumber);
		$voter->setElection($constituency->getElection());

		try {
			$this->beginTransaction();
			$this->getEntityManager()->persist($voter);
			$this->getEntityManager()->flush();
			$this->commit();
			return $hash;
		} catch (DBALException $e) {
			return null;
		}
	}

	/**
	 * Adds a new vote to the system.
	 *
	 * @param string $voterHash   The secret token of the voter (hash).
	 * @param int    $candidateId The first vote (candidate).
	 * @param int    $stateListId The second vote (party / state list).
	 *
	 * @return bool True in case of success; false otherwise.
	 */
	public function vote($voterHash, $candidateId, $stateListId)
	{
		$voter = $this->byHash($voterHash);
		if ($voter == null || $voter->getVoted()) {
			return false;
		}

		try {
			$this->beginTransaction();

			if (!is_null($candidateId)) {
				$firstVoteQuery = $this->prepareQuery("INSERT INTO first_result (candidate_id) VALUES (:candidate_id)");
				$firstVoteQuery->bindValue('candidate_id', $candidateId);
				$firstVoteQuery->execute();
			}

			if (!is_null($stateListId)) {
				$secondVoteQuery = $this->prepareQuery("INSERT INTO second_result (state_list_id, constituency_id) VALUES (:state_list_id, :constituency_id)");
				$secondVoteQuery->bindValue('state_list_id', $stateListId);
				$secondVoteQuery->bindValue('constituency_id', $voter->getConstituency()->getId());
				$secondVoteQuery->execute();
			}

			$voter->setVoted(true);
			$this->getEntityManager()->merge($voter);

			$this->getEntityManager()->flush();
			$this->commit();

			return true;
		} catch (DBALException $e) {
			return false;
		}
	}

}
