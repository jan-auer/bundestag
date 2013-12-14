<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 06.12.13
 * Time: 21:47
 */

namespace Btw\Bundle\BtwAppBundle\Controller;


use Btw\Bundle\BtwAppBundle\Form\ElectionAnalysisForm;
use Btw\Bundle\BtwAppBundle\Model\ElectionAnalysisModel;
use DateTime;
use Doctrine\Tests\ORM\Id\AssignedGeneratorTest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DetailController extends Controller
{
	public function indexAction($year)
	{
		return $this->render('BtwAppBundle:Analysis:details.html.twig', array('year' => $year));

	}

	public function electionResultsAction($year)
	{
		//INJECT SERVICES
		$electionProvider = $this->get("btw_election_provider");
		$stateProvider = $this->get("btw_state_provider");
		$constituencyProvider = $this->get('btw_constituency_provider');
		$partyProvider = $this->get('btw_party_provider');
		$mdbProvider = $this->get('btw_members_of_bundestag_provider');
		$partyResultsProvider = $this->get('btw_party_results_provider');

		//INIT
		$election = $electionProvider->forYear($year);
		$prevElection = $electionProvider->getPreviousElectionFor($election);

		//DATA

		//1. States
		$states = array();
		foreach($stateProvider->getAllForElection($election) as $state)
		{
			$states[] = array('id' => $state->getId(),
							  'name' => $state->getName(),
							  'population' => $state->getPopulation());
		}

		//2. Constituencies
		$constituencies = array();
		foreach($constituencyProvider->getAllDetailsForElection($election, $prevElection) as $constituency)
		{
			$constituencies[] = $constituency->toArray();
		}

		//3. Parties
		$parties = array();
		foreach($partyProvider->getAllForElection($election) as $party)
		{
			$parties[] = array('id' => $party->getId(),
							   'name' => $party->getName(),
							   'abbr' => $party->getAbbreviation(),
							   'color' => $party->getColor());
		}

		//4. MdBs
		$members = array();
		foreach($mdbProvider->getAllForElection($election) as $member)
		{
			$members[] = $member->toArray();
		}

		//5. Votes
		$votes = array();
		foreach($partyResultsProvider->getVotesForElection($election, $prevElection) as $result)
		{
			$votes[] = $result->toArray();
		}

		//6. Seats
		$seats = array();
		foreach($partyResultsProvider->getSeatsForElection($election) as $result)
		{
			$seats[] = $result->toArray();
		}

		//RESULT
		$result = array('states' => $states,
						'constituencies' => $constituencies,
						'parties' => $parties,
						'members' => $members,
						'votes' => $votes,
						'seats' => $seats);
		return new Response(json_encode($result));
	}

	public function closestAction($partyId)
	{
		$partyProvider = $this->get('btw_party_provider');
		$closestProvider = $this->get('btw_closest_candidates_provider');

		$party = $partyProvider->byId($partyId);

		$closests = array();
		foreach($closestProvider->forParty($party) as $closest)
		{
			$closests[] = array('name' => $closest->getName(),
							   'constituency' => $closest->getConstituencyName(),
							   'type' => $closest->getType());
		}

		$result = array('candidates' => $closests,
						'abbr' => $party->getAbbreviation(),
		                'name' => $party->getName(),
						'color' => $party->getColor());

		return $this->render('BtwAppBundle:Analysis:closest.html.twig', $result);
	}
}
