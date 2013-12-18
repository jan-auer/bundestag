<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 18/12/13
 * Time: 18:19
 */

namespace Btw\Bundle\BtwAppBundle\Services;


use Doctrine\ORM\EntityManager;

class BenchmarkProvider extends AbstractProvider
{

	private $electionProvider;

	function __construct(EntityManager $entityManager, ElectionProvider $electionProvider)
	{
		parent::__construct($entityManager);
		$this->electionProvider = $electionProvider;
	}

	public function executeQuery1($year)
	{
		$election = $this->electionProvider->forYear($year);
		if (!is_null($election)) {
			$query = $this->prepareQuery("
				SELECT p.name AS partyName,p.abbreviation  AS partyAbbr, sum(pss.seats) AS seats
				FROM party_state_seats pss
				JOIN election e USING(election_id)
				JOIN party p USING(party_id)
				WHERE e.election_id= :electionId
				GROUP BY p.name, p.abbreviation
			");

			$query->bindValue('electionId', $election->getId());
			return array(
				'bundestag' => $this->executeQuery($query, function ($result) {
						return $result;
					})
			);
		}

		return null;
	}

	public function executeQuery2($year)
	{
		$election = $this->electionProvider->forYear($year);
		if (!is_null($election)) {
			$query = $this->prepareQuery("
					SELECT c.name AS name, s.name as state, co.name AS constituency, p.name AS party, bc.directCandidate
				   	FROM bundestag_candidates bc
			 	   	JOIN candidate c USING(candidate_id, party_id)
			 	   	JOIN state s USING(state_id)
			 	   	LEFT JOIN party p USING(party_id)
			 	   	LEFT JOIN constituency co USING(constituency_id)
					WHERE c.election_id = :electionId");

			$query->bindValue('electionId', $election->getId());
			return array(
				'bundestag' => $this->executeQuery($query, function ($result) {
						return $result;
					})
			);
		}

		return null;
	}

} 