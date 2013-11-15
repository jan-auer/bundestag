<?php
namespace Btw\Bundle\ImporterBundle\Import;

use Btw\Bundle\ImporterBundle\Parser\HtmlParser;
use Btw\Bundle\PersistenceBundle\Entity\Candidate;
use Btw\Bundle\PersistenceBundle\Entity\ConstituencyCandidacy;
use Btw\Bundle\PersistenceBundle\Entity\Election;
use Btw\Bundle\PersistenceBundle\Entity\FirstResult;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The importer class loads election data into the database.
 *
 * @package Btw\Bundle\ImporterBundle\Import
 */
class Importer
{
	const ELECTIONS_ADMINISTRATION_CONSTITUENCY_URL = 'http://www.bundeswahlleiter.de/de/bundestagswahlen/BTW_BUND_%s/ergebnisse/wahlkreisergebnisse/l%s/wk%s/';

	/** @var  EntityManager */
	private $em;
	/** @var  EntityFactory */
	private $factory;
	/** @var  Array of Keys which should be ignored in the result table on website of elections administration. */
	private $electionsAdministrationIgnoreKeys;
	/** @var  Array of first results for free candidates. */
	private $freeConstituencyCandidateResults;
	/** @var  Array of parties where the key is the column in results.csv */
	private $columnParties;
	/** @var  Array of constituencies where the key is the row in results.csv */
	private $rowConstituencies;

	private $output;

	function __construct(EntityManager $entityManager, OutputInterface $output)
	{
		$this->output = $output;
		$this->em = $entityManager;
		$this->electionsAdministrationIgnoreKeys = array('Wahlberechtigte', 'Wähler', 'Ungültige', 'Gültige');
		$this->freeConstituencyCandidateResults = array();
		$this->columnParties = array();
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

		$this->output->writeln("Importing election...");
		$electionObj = $this->importElection($election);
		$this->output->writeln("Importing states...");
		$this->importStates($demography);
		$this->output->writeln("Importing constituencies...");
		$constituencies = $this->importConstituencies($demography);
		$this->output->writeln("Importing parties...");
		$parties = $this->importParties($results);
		$this->output->writeln("Importing state lists...");
		$this->importStateLists($results);
		$this->output->writeln("Importing candidates...");
		$this->importCandidates($candidates);
		$this->output->writeln("Importing free candidates...");
		$this->importFreeCandidates($electionObj, $parties, $constituencies);
		$this->output->writeln("Importing results...");
		$this->importResults($results);

		$this->em->flush();
	}

	private function importElection(array &$data)
	{
		$election = $this->factory->createElection($data[0]);
		$this->em->persist($election);
		return $election;
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

	private function importConstituencies(array $data)
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
		$k = 0;
		foreach ($data[0] as $column) {
			$k++;
			if (empty($column)) {
				continue;
			}
			if ($i++ < 7) continue;
			if ($column == 'Übrige') continue;

			$party = $this->factory->createParty($column);
			$parties[] = $party;
			$this->em->persist($party);
			$this->columnParties[$k - 1] = $party;
		}
		return $parties;
	}

	private function importStateLists(array $data)
	{
		foreach ($data as $row) {
			if (count($row) < 2) continue;
			if ($row[2] == 99) {
				$stateName = $row[1];

				for ($i = 19; $i < count($row) - 2; $i += 4) {
					$partyAbbr = $data[0][$i];
					if($partyAbbr=='Übrige') continue;
					$firstresult = $row[$i];
					$secondresult = $row[$i + 2];

					$stateList = $this->factory->createStateList($stateName, $partyAbbr);
					$this->em->persist($stateList);
				}
			}
		}
	}

	private function importCandidates(array &$data)
	{
		foreach ($data as $row) {
			$name = $row[0];
			$partyAbbr = $row[2];
			$constituencyNo = $row[3];

			$candidate = $this->factory->createCandidate($name, $partyAbbr);
			if (is_null($candidate)) continue;

			$this->em->persist($candidate);
			if (empty($constituencyNo)) continue;

			$constituencyCandidacy = $this->factory->createConstituencyCandidacy($candidate, $constituencyNo);
			$this->em->persist($constituencyCandidacy);
		}
	}

	private function importFreeCandidates(Election $election, array $parties, array $constituencies)
	{
		foreach ($constituencies as $constituency) {
			$electionNo =date("y", $election->getDate()->getTimestamp());
			$stateNo = str_pad($constituency->getState()->getNumber(), 2, '0', STR_PAD_LEFT);
			$constituencyNo = str_pad($constituency->getNumber(), 3, '0', STR_PAD_LEFT);

			$url = sprintf(Importer::ELECTIONS_ADMINISTRATION_CONSTITUENCY_URL, $electionNo, $stateNo, $constituencyNo);

			$results = HtmlParser::parseResultTableBody($url);

			foreach ($this->electionsAdministrationIgnoreKeys as $ignoreKey) {
				unset($results[$ignoreKey]);
			}

			foreach ($parties as $party) {
				unset($results[$party->getName()]);
			}

			foreach ($results as $name => $votes) {
				if ($name == 'ÖDP / Familie ..') continue;

				$freeCandidate = $this->factory->createCandidate($name);
				if (is_null($freeCandidate)) continue;
				$this->em->persist($freeCandidate);

				$constituencyCandidacy = $this->factory->createConstituencyCandidacy($freeCandidate, $constituency->getNumber());
				$this->em->persist($constituencyCandidacy);

				$this->freeConstituencyCandidateResults[] = array($constituencyCandidacy, $votes);
			}
		}
	}

	private function importResults(array &$data)
	{
		$rowI = 0;
		$rowCount = count($data);
		foreach ($data as $row) {
			if ($rowI++ < 3 || $rowI == $rowCount || (count($row) == 1 && is_null($row[0]))) continue; // skip first three rows, the last row and skip empty rows

			$stateNo = $row[2];
			if ($stateNo == 99) continue;

			$constituencyNo = $row[0];

			foreach ($this->columnParties as $column => $party) {
				//firstresults for candidates with party

				$firstResultCount = $row[$column];

				if ($firstResultCount > 0) {
					$aggrFirstResult = $this->factory->createAggregatedFirstResultRow($constituencyNo, $party, $firstResultCount);
					$this->em->persist($aggrFirstResult);
				}

				//secondresults for party
				$secondResultCount = $row[$column + 2];
				if ($secondResultCount > 0) {
					$aggrSecondResult = $this->factory->createAggregatedSecondResult($party, $stateNo, $constituencyNo, $secondResultCount);
					$this->em->persist($aggrSecondResult);
				}

			}
		}

		//free candidates
		$k = 0;
		foreach ($this->freeConstituencyCandidateResults as $freeConstituencyCandidateResults) {
			$freeConstituencyCandidate = $freeConstituencyCandidateResults[0];
			$votes = $freeConstituencyCandidateResults[1];

			$aggrFreeFirstResult = $this->factory->createAggregatedFirstResult($freeConstituencyCandidate, $votes);
			$this->em->persist($aggrFreeFirstResult);
		}
	}

}
