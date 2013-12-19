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
			return $this->executeQuery($query, function ($result) {
				return $result;
			});
		}

		return null;
	}

	public function executeQuery2($year)
	{
		$election = $this->electionProvider->forYear($year);
		if (!is_null($election)) {
			$query = $this->prepareQuery("
					SELECT c.name AS name, s.name AS state, co.name AS constituency, p.name AS party, bc.directCandidate
				   	FROM bundestag_candidates bc
			 	   	JOIN candidate c USING(candidate_id, party_id)
			 	   	JOIN state s USING(state_id)
			 	   	LEFT JOIN party p USING(party_id)
			 	   	LEFT JOIN constituency co USING(constituency_id)
					WHERE c.election_id = :electionId");

			$query->bindValue('electionId', $election->getId());
			return $this->executeQuery($query, function ($result) {
				return $result;
			});
		}

		return null;
	}

	public function executeQuery31($constituencyId)
	{
		$query = $this->prepareQuery("
					SELECT ct.turnout, ct.voters, ct.electives
				   	FROM constituency_turnout ct
			 	   	JOIN constituency c USING(constituency_id)
					WHERE constituency_id = :constituencyId");

		$query->bindValue('constituencyId', $constituencyId);
		$result = $this->executeQuery($query, function ($result) {
			return $result;
		});

		if(is_array($result) && count($result)>0) {
			return $result[0];
		}
		return null;
	}

	public function executeQuery32($constituencyId)
	{
		$query = $this->prepareQuery("
					SELECT c.name, p.name AS partyName, p.abbreviation AS partyAbbreviation
				   	FROM constituency_winners cw
			 	   	JOIN candidate c USING(candidate_id)
			 	   	JOIN party p USING(party_id)
					WHERE constituency_id = :constituencyId");

		$query->bindValue('constituencyId', $constituencyId);
		$result = $this->executeQuery($query, function ($result) {
			return $result;
		});
		if(is_array($result) && count($result)>0) {
			return $result[0];
		}
		return null;

	}

	public function executeQuery33($constituencyId)
	{
		$query = $this->prepareQuery("
					SELECT p.name AS partyName, p.abbreviation AS partyAbbreviation, cv.absolutevotes, cv.percentualvotes
				   	FROM constituency_votes cv
			 	   	JOIN party p USING(party_id)
					WHERE constituency_id = :constituencyId");

		$query->bindValue('constituencyId', $constituencyId);
		return $this->executeQuery($query, function ($result) {
			return $result;
		});
	}

	public function executeQuery34($constituencyId)
	{
		$query = $this->prepareQuery("
					SELECT cvh.olddate, cvh.newdate, cvh.party_abbreviation as partyAbbreviation, cvh.oldabsolutevotes, cvh.newabsolutevotes, cvh.oldtotalvotes, cvh.newtotalvotes
				   	FROM constituency_votes_history cvh,
				   	constituency c
					WHERE c.name = cvh.constituency_name AND c.constituency_id = :constituencyId");

		$query->bindValue('constituencyId', $constituencyId);
		return $this->executeQuery($query, function ($result) {
			return $result;
		});
	}

	public function executeQuery4($year)
	{
		$election = $this->electionProvider->forYear($year);
		if (!is_null($election)) {
			$query = $this->prepareQuery("
				SELECT c.name as constituencyname, fp.name as firstPartyName, fp.abbreviation as firstPartyAbbreviation, fp.color as firstPartyColor, sp.name as secondPartyName, sp.abbreviation as secondPartyAbbreviation, sp.color as secondPartyColor
				FROM constituency_winner_parties cwp
				JOIN party fp ON fp.party_id = firstvotepartyid
				JOIN party sp ON sp.party_id = firstvotepartyid
				JOIN constituency c USING (constituency_id)
				WHERE cwp.election_id= :electionId
			");

			$query->bindValue('electionId', $election->getId());
			return $this->executeQuery($query, function ($result) {
				return $result;
			});
		}

		return null;
	}

	public function executeQuery5($year)
	{
		$election = $this->electionProvider->forYear($year);
		if (!is_null($election)) {
			$query = $this->prepareQuery("
				SELECT s.name as statename, p.name as partyname, p.abbreviation as partyabbreviation, p.color as partycolor, sps.overhead
				FROM state_party_seats sps
				JOIN state s USING(state_id)
				JOIN party p USING(party_id, election_id)
				WHERE p.election_id= :electionId
			");

			$query->bindValue('electionId', $election->getId());
			return $this->executeQuery($query, function ($result) {
				return $result;
			});
		}

		return null;
	}

	public function executeQuery6($year)
	{
		$election = $this->electionProvider->forYear($year);
		if (!is_null($election)) {
			$query = $this->prepareQuery("
				SELECT ca.name as candidateName, p.name as partyName, p.abbreviation as partyAbbreviation, p.color as partyColor, tcc.ranking, tcc.type
				FROM top_close_constituency_candidates tcc
				JOIN party p USING(party_id, election_id)
				JOIN constituency c USING (constituency_id, election_id)
				JOIN candidate ca USING (candidate_id, election_id)
				WHERE p.election_id= :electionId AND ranking <=10
				ORDER BY p.abbreviation, ranking ASC
			");

			$query->bindValue('electionId', $election->getId());
			return $this->executeQuery($query, function ($result) {
				return $result;
			});
		}

		return null;
	}

}