<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 11.12.13
 * Time: 19:19
 */

namespace Btw\Bundle\BtwAppBundle\Services;


use Btw\Bundle\BtwAppBundle\Model\MemberOfBundestag;
use Btw\Bundle\PersistenceBundle\Entity\Constituency;
use Btw\Bundle\PersistenceBundle\Entity\Election;
use Btw\Bundle\PersistenceBundle\Entity\State;
use Doctrine\ORM\EntityManager;

class MembersOfBundestagProvider {

	/** @var  EntityManager */
	protected $em;

	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	public function forCountry(Election $election)
	{
		$membersOfBundestag = array();

		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT c.name AS name, p.abbreviation AS party FROM Candidate c, constituency_winners cw, party p WHERE c.candidate_id=cw.candidate_id AND c.election_id=:electionId AND c.party_id=p.party_id");
		$statement->bindValue('electionId', $election->getId());
		foreach($statement->fetchAll() as $member)
		{
			$memberOfBundestag = new MemberOfBundestag();
			$memberOfBundestag->setName($member['name']);
			$memberOfBundestag->setPartyAbbreviation($member['party']);
			$memberOfBundestag->setIsDirect(true);
			$membersOfBundestag[] = $memberOfBundestag;
		}

		$statement = $connection->prepare("SELECT c.name AS name, p.abbreviation AS party FROM Candidate c, elected_candidates ec, party p WHERE c.candidate_id=ec.candidate_id AND c.election_id=:electionId AND c.party_id=p.party_id AND candidate_id NOT IN (SELECT c.candidate_id AS name FROM Candidate c, constituency_winners cw WHERE c.candidate_id=cw.candidate_id AND c.election_id=:electionId)");
		$statement->bindValue('electionId', $election->getId());
		$statement->execute();
		foreach($statement->fetchAll() as $member)
		{
			$memberOfBundestag = new MemberOfBundestag();
			$memberOfBundestag->setName($member['name']);
			$memberOfBundestag->setPartyAbbreviation($member['party']);
			$memberOfBundestag->setIsDirect(false);
			$membersOfBundestag[] = $memberOfBundestag;
		}

		return $membersOfBundestag;
	}

	public function forState(State $state)
	{
		$membersOfBundestag = array();

		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT c.name AS name, p.abbreviation AS party FROM Candidate c, elected_candidates ec, constituency_candidacy cc, constituency ct, party p WHERE c.candidate_id=ec.candidate_id AND c.candidate_id=cc.candidate_id AND sc.constituency_id=ct.constituency_id AND ct.state_id=:stateId AND c.party_id=p.party_id");
		$statement->bindValue('stateId', $state->getId());
		$statement->execute();
		foreach($statement->fetchAll() as $member)
		{
			$memberOfBundestag = new MemberOfBundestag();
			$memberOfBundestag->setName($member['name']);
			$memberOfBundestag->setPartyAbbreviation($member['party']);
			$memberOfBundestag->setIsDirect(true);
			$membersOfBundestag[] = $memberOfBundestag;
		}

		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT c.name AS name, p.abbreviation AS party FROM Candidate c, elected_candidates ec, state_candidacy sc, state_list sl, party p WHERE c.candidate_id=ec.candidate_id AND c.candidate_id=sc.candidate_id AND sc.state_list_id=sl.state_list_id AND sl.state_id=:stateId AND c.party_id=p.party_id");
		$statement->bindValue('stateId', $state->getId());
		$statement->execute();
		foreach($statement->fetchAll() as $member)
		{
			$memberOfBundestag = new MemberOfBundestag();
			$memberOfBundestag->setName($member['name']);
			$memberOfBundestag->setPartyAbbreviation($member['party']);
			$memberOfBundestag->setIsDirect(false);
			$membersOfBundestag[] = $memberOfBundestag;
		}

		return $membersOfBundestag;
	}

	public function forConstituency(Constituency $constituency)
	{
		$membersOfBundestag = array();

		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT c.name AS name, p.abbreviation AS party FROM Candidate c, elected_candidates ec, constituency_candidacy cc, party p WHERE c.candidate_id=ec.candidate_id AND c.candidate_id=cc.candidate_id AND c.party_id=p.party_id AND cc.constituency_id=:constituencyId");
		$statement->bindValue('constituencyId', $constituency->getId());
		$statement->execute();
		foreach ($statement->fetchAll() as $member) {
			$memberOfBundestag = new MemberOfBundestag();
			$memberOfBundestag->setName($member['name']);
			$memberOfBundestag->setPartyAbbreviation($member['party']);
			$memberOfBundestag->setIsDirect(true);
			$membersOfBundestag[] = $memberOfBundestag;
		}

		$connection = $this->em->getConnection();
		$statement = $connection->prepare("SELECT c.name AS name, p.abbreviation AS party FROM Candidate c, elected_candidates ec, constituency_candidacy cc, party p WHERE c.candidate_id=ec.candidate_id AND c.candidate_id=cc.candidate_id AND ct.constituency_id=:constituencyId AND c.party_id=p.party_id");
		$statement->bindValue('constituencyId', $constituency->getId());
		$statement->execute();
		foreach($statement->fetchAll() as $member)
		{
			$memberOfBundestag = new MemberOfBundestag();
			$memberOfBundestag->setName($member['name']);
			$memberOfBundestag->setPartyAbbreviation($member['party']);
			$memberOfBundestag->setIsDirect(true);
			$membersOfBundestag[] = $memberOfBundestag;
		}
	}
} 