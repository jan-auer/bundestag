<?php
namespace Btw\Bundle\ImporterBundle\Import;

use Btw\Bundle\ImporterBundle\CSV\HtmlParser;
use Btw\Bundle\PersistenceBundle\Entity\Candidate;
use Btw\Bundle\PersistenceBundle\Entity\ConstituencyCandidacy;
use Doctrine\ORM\EntityManager;

/**
 * The importer class loads election data into the database.
 *
 * @package Btw\Bundle\ImporterBundle\Import
 */
class Importer
{
	const ELECTIONS_ADMINISTRATION_CONSTITUENCY_URL = 'http://www.bundeswahlleiter.de/de/bundestagswahlen/BTW_BUND_13/ergebnisse/wahlkreisergebnisse/l%s/wk%s/';

	/** @var  EntityManager */
	private $em;
	/** @var  EntityFactory */
	private $factory;
	/** @var  Array of Keys which should be ignored in the result table on website of elections administration. */
	private $electionsAdministrationIgnoreKeys;

	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
		$this->electionsAdministrationIgnoreKeys = array('Wahlberechtigte', 'Wähler', 'Ungültige', 'Gültige');
	}

	/**
	 * Imports the given election data into the database.
	 * Before inserting, all information is correctly wired up.
	 *
	 * @param array $election An array containing the collection number and date.
	 * @param array $demography An array containing all states and constituencies.
	 * @param array $candidates An array containing all candidates and their parties.
	 * @param array $results An array containing aggregated results of the election.
	 */
	public function import(array &$election, array &$demography, array &$candidates, array &$results)
	{
		$this->factory = new EntityFactory();

		$this->importElection($election);
		$this->importStates($demography);
		$constituencies = $this->importConstituencies($demography);
		$parties = $this->importParties($results);
		$this->importCandidates($candidates);
		$this->importFreeCandidates($parties, $constituencies);
		$this->importResults($results);

		$this->em->flush();
	}

	private function importElection(array &$data)
	{
		$election = $this->factory->createElection($data[0]);
		$this->em->persist($election);
	}

	private function importStates(array &$data)
	{
		$states = array();
		foreach ($data as $row) {
			if ($row[1] < 900 || $row[1] > 920) continue;
			$state = $this->factory->createState($row);
			$states[] = $state;
			$this->em->persist($state);
		}
		return $states;
	}

	private function importConstituencies(array &$data)
	{
		$constituencies = array();
		foreach ($data as $row) {
			if ($row[1] > 900) continue;
			$constituency = $this->factory->createConstituency($row);
			$constituencies[] = $constituency;
			$this->em->persist($constituency);
		}
		return $constituencies;
	}

	private function importParties(array &$data)
	{
		$parties = array();
		$i = 0;
		foreach ($data[0] as $column) {
			if (empty($column)) continue;
			if ($i++ < 7) continue;
			if ($column == 'Übrige') continue;
			$party = $this->factory->createParty($column);
			$parties[] = $party;
			$this->em->persist($party);
		}
		return $parties;
	}

	private function importCandidates(array &$data)
	{
	}

	private function importFreeCandidates(array $parties, array $constituencies)
	{
		foreach ($constituencies as $constituency) {
			$stateNo = str_pad($constituency->getState()->getNumber(), 2, '0', STR_PAD_LEFT);
			$constituencyNo = str_pad($constituency->getNumber(), 3, '0', STR_PAD_LEFT);

			$stateNo = "09";
			$constituencyNo = "221";
			$url = sprintf(Importer::ELECTIONS_ADMINISTRATION_CONSTITUENCY_URL, $stateNo, $constituencyNo);

			$results = HtmlParser::parseResultTableBody($url);

			foreach ($this->electionsAdministrationIgnoreKeys as $ignoreKey) {
				unset($results[$ignoreKey]);
			}

			foreach ($parties as $party) {
				unset($results[$party->getName()]);
			}

			foreach ($results as $name => $votes) {
				$freeCandidate = new Candidate();
				$freeCandidate->setName($name);
				$this->em->persist($freeCandidate);

				$constituencyCandidacy = new ConstituencyCandidacy();
				$constituencyCandidacy->setConstituency($constituency);
				$constituencyCandidacy->setCandidate($freeCandidate);
				$this->em->persist($constituencyCandidacy);
				$this->em->flush();
				exit;
			}

		}
	}

	private function importResults(array &$data)
	{
	}

}
