<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 07.01.14
 * Time: 16:31
 */

namespace Btw\Bundle\BtwAppBundle\Services;

use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Btw\Bundle\PersistenceBundle\Entity\Voter;
use Doctrine\DBAL\DBALException;
use PDOException;

class VoterProvider
	extends AbstractProvider
{

	/**
	 * @param $hash
	 *
	 * @return Voter
	 */
	public function byHash($hash)
	{
		$voter = $this->em->getRepository('Btw\Bundle\PersistenceBundle\Entity\Voter')->findBy(array('hash' => $hash));
		if (is_array($voter) && count($voter)) {
			return $voter[0];
		}
		return null;
	}

	/**
	 * @param  $identityNumber
	 * @param Constituency $constituency
	 * @return bool
	 */
	public function createVoter($identityNumber, Constituency $constituency)
	{
		$hash = md5($identityNumber);

		$this->beginTransaction();

		$query = $this->prepareQuery("INSERT INTO voter (identityNumber, hash, constituency_id, voted, election_id) VALUES (:identityNumber, :hash, :constituencyId, :voted, :electionId)");
		$query->bindValue('identityNumber', $identityNumber);
		$query->bindValue('hash', $hash);
		$query->bindValue('constituencyId', $constituency->getId());
		$query->bindValue('voted', 'FALSE');
		$query->bindValue('electionId', $constituency->getElection()->getId());

		try {
			$this->executeUpdateQuery($query);
			$this->commit();
			return $hash;
		} catch (DBALException $e) {
			$this->rollback();
			return null;
		}
	}

	/**
	 * @param $voterHash hash of voter
	 * @param $candidateId first vote
	 * @param $stateListId second vote
	 * @return bool true in case of successfull vote, false otherwise
	 */
	public function vote($voterHash, $candidateId, $stateListId)
	{
		$voter = $this->byHash($voterHash);
		if ($voter == null || $voter->getVoted()) {
			return false;
		} else {
			$this->beginTransaction();

			$firstVoteQuery = $this->prepareQuery("INSERT INTO first_result (candidate_id) VALUES (:candidateId)");
			$firstVoteQuery->bindValue('candidateId', $candidateId);
			$firstVote = $this->executeUpdateQuery($firstVoteQuery);

			$secondVoteQuery = $this->prepareQuery("INSERT INTO second_result (state_list_id, constituency_id) VALUES (:stateListId, :constituencyId)");
			$secondVoteQuery->bindValue('stateListId', $stateListId);
			$secondVoteQuery->bindValue('constituencyId', $voter->getConstituency()->getId());
			$secondVote = $this->executeUpdateQuery($secondVoteQuery);

			$votedQuery = $this->prepareQuery("UPDATE voter SET voted = TRUE WHERE hash = :hash");
			$votedQuery->bindValue('hash', $voterHash);
			$voted = $this->executeUpdateQuery($votedQuery);

			if ($firstVote && $secondVote && $voted) {
				try {
					$this->commit();
					return true;
				} catch (\Exception $e) {
					return false;
				}
			}
		}

	}
}
