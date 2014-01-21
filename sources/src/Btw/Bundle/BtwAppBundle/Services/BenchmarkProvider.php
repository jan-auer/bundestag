<?php

namespace Btw\Bundle\BtwAppBundle\Services;

use Doctrine\ORM\EntityManager;

/**
 * Executes specific queries for benchmarking purposes.
 */
class BenchmarkProvider extends AbstractProvider
{

	/** @var ElectionProvider */
	private $electionProvider;

	/**
	 * @param EntityManager    $entityManager
	 * @param ElectionProvider $electionProvider
	 */
	function __construct(EntityManager $entityManager, ElectionProvider $electionProvider)
	{
		parent::__construct($entityManager);
		$this->electionProvider = $electionProvider;
	}

	/**
	 * Computes the distribution of seats for one election.
	 */
	public function executeQuery1($year)
	{
		$election = $this->getElectionProvider()->forYear($year);
		if (is_null($election)) {
			return null;
		}

		$query = $this->prepareQuery("
			SELECT p.name AS partyname,p.abbreviation AS partyabbr, sum(pss.seats) AS seats
			FROM party_state_seats pss
			JOIN election e USING(election_id)
			JOIN party p USING(party_id)
			WHERE e.election_id= :id
			GROUP BY p.name, p.abbreviation");

		$query->bindValue('id', $election->getId());
		return $this->executeQuery($query);
	}

	/**
	 * Computes a list of members in the Bundestag.
	 */
	public function executeQuery2($year)
	{
		$election = $this->getElectionProvider()->forYear($year);
		if (is_null($election)) {
			return null;
		}

		$query = $this->prepareQuery("
			SELECT c.name AS name, s.name AS state, co.name AS constituency, p.name AS party, bc.direct_candidate
		    FROM bundestag_candidates bc
	        JOIN candidate c USING(candidate_id, party_id)
	        JOIN state s USING(state_id)
	        LEFT JOIN party p USING(party_id)
	        LEFT JOIN constituency co USING(constituency_id)
			WHERE c.election_id = :id");

		$query->bindValue('id', $election->getId());
		return $this->executeQuery($query);
	}

	/**
	 * Computes the number of voters and turnout for a constituency.
	 */
	public function executeQuery31_2($constituencyId)
	{
		$query = $this->prepareQuery("
			SELECT c.name AS constituencyname, c.number AS constituencynumber, ct.turnout, ct.voters,
			       ct.electives, ca.name AS constituencywinner, p.name AS winnerpartyname,
			       p.abbreviation AS winnerpartyabbreviation
			FROM constituency_turnout ct
			JOIN constituency_winners cw  USING(constituency_id)
			JOIN constituency c USING(constituency_id)
			JOIN candidate ca USING(candidate_id)
			JOIN party p USING(party_id)
			WHERE constituency_id = :id");

		$query->bindValue('id', $constituencyId);
		$result = $this->executeQuery($query);
		return count($result) > 0 ? $result[0] : null;
	}

	/**
	 * Computes the number of voters for all parties in a constituency.
	 */
	public function executeQuery33_4($constituencyId)
	{
		$query = $this->prepareQuery("
			SELECT p.name AS partyname, p.abbreviation AS partyabbreviation, cv.absolutevotes,
			       cv.percentualvotes, cvh.olddate, cvh.oldabsolutevotes
			FROM constituency_votes cv
				JOIN constituency c USING(constituency_id)
				JOIN election e USING(election_id)
				JOIN party p USING(party_id)
				LEFT JOIN constituency_votes_history cvh ON  c.name = cvh.constituency_name
					AND cvh.party_abbreviation = p.abbreviation
					AND cvh.newdate = e.date
			WHERE constituency_id = :id");

		$query->bindValue('id', $constituencyId);
		$result = $this->executeQuery($query);
		return count($result) > 0 ? $result[0] : null;
	}

	/**
	 * Computes the winning parties for first and second results.
	 */
	public function executeQuery4($year)
	{
		$election = $this->getElectionProvider()->forYear($year);
		if (is_null($election)) {
			return null;
		}

		$query = $this->prepareQuery("
			SELECT c.name AS constituencyname, fp.name AS firstpartyname, fp.abbreviation AS firstpartyabbreviation,
			       fp.color AS firstpartycolor, sp.name AS secondpartyname, sp.abbreviation AS secondpartyabbreviation,
			       sp.color AS secondpartycolor
			FROM constituency_winner_parties cwp
			JOIN party fp ON fp.party_id = firstvotepartyid
			JOIN party sp ON sp.party_id = firstvotepartyid
			JOIN constituency c USING (constituency_id)
			WHERE cwp.election_id= :id");

		$query->bindValue('id', $election->getId());
		return $this->executeQuery($query);
	}

	/**
	 * Computes overhang seats for all parties in a specific election.
	 */
	public function executeQuery5($year)
	{
		$election = $this->getElectionProvider()->forYear($year);
		if (is_null($election)) {
			return null;
		}

		$query = $this->prepareQuery("
			SELECT s.name AS statename, p.name AS partyname, p.abbreviation AS partyabbreviation,
			       p.color AS partycolor, sps.overhead
			FROM state_party_seats sps
			JOIN state s USING(state_id)
			JOIN party p USING(party_id, election_id)
			WHERE p.election_id= :id");

		$query->bindValue('id', $election->getId());
		return $this->executeQuery($query);
	}

	/**
	 * Computes the most concise constituency winners.
	 */
	public function executeQuery6($year)
	{
		$election = $this->getElectionProvider()->forYear($year);
		if (is_null($election)) {
			return null;
		}

		$query = $this->prepareQuery("
			SELECT ca.name AS candidatename, p.name AS partyname, p.abbreviation AS partyabbreviation,
			       p.color AS partycolor, tcc.ranking, tcc.type
			FROM top_close_constituency_candidates tcc
			JOIN party p USING(party_id, election_id)
			JOIN constituency c USING (constituency_id, election_id)
			JOIN candidate ca USING (candidate_id, election_id)
			WHERE p.election_id= :id AND ranking <=10
			ORDER BY p.abbreviation, ranking ASC");

		$query->bindValue('id', $election->getId());
		return $this->executeQuery($query);
	}

	/**
	 * @return ElectionProvider
	 */
	private function getElectionProvider()
	{
		return $this->electionProvider;
	}

}
