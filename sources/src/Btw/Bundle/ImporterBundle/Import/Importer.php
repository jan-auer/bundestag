<?php
namespace Btw\Bundle\ImporterBundle\Import;

use Btw\Bundle\ImporterBundle\Parser\HtmlParser;
use Btw\Bundle\PersistenceBundle\Entity\Candidate;
use Btw\Bundle\PersistenceBundle\Entity\ConstituencyCandidacy;
use Btw\Bundle\PersistenceBundle\Entity\FirstResult;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Output\OutputInterface;

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
	/** @var  Array of first results for free candidates. */
	private $freeConstituencyCandidateResults;

	private $output;

	function __construct(EntityManager $entityManager, OutputInterface $output)
	{
		$this->output = $output;
		$this->em = $entityManager;
		$this->electionsAdministrationIgnoreKeys = array('Wahlberechtigte', 'Wähler', 'Ungültige', 'Gültige');
		$this->freeConstituencyCandidateResults = array();
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
		$this->importElection($election);
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
		$this->importFreeCandidates($parties, $constituencies);
		$this->output->writeln("Importing results...");
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

	private function importStateLists(array $data)
	{
		foreach ($data as $row) {
			if (count($row) < 2) continue;
			if ($row[2] == 99) {
				$stateName = $row[1];

				for ($i = 19; $i < count($row) - 2; $i += 4) {
					$partyAbbr = $data[0][$i];
					$firstresult = $row[$i];
					$secondresult = $row[$i + 2];

					if (!empty($firstresult) && !empty($secondresult)) {
						$stateList = $this->factory->createStateList($stateName, $partyAbbr);
						$this->em->persist($stateList);
					}
				}
			}
		}
	}

	private function importCandidates(array &$data)
	{
		foreach($data as $row){
			$name = $row[0];
			$partyAbbr = $row[2];
			$constituencyNo = $row[3];

			$candidate = $this->factory->createCandidate($name, $partyAbbr);
			if(is_null($candidate)) continue;

			$this->em->persist($candidate);
			if(empty($constituencyNo))continue;

			$constituencyCandidacy = $this->factory->createConstituencyCandidacy($candidate, $constituencyNo);
			$this->em->persist($constituencyCandidacy);
		}
	}

	private function importFreeCandidates(array $parties, array $constituencies)
	{
		foreach ($constituencies as $constituency) {
			$stateNo = str_pad($constituency->getState()->getNumber(), 2, '0', STR_PAD_LEFT);
			$constituencyNo = str_pad($constituency->getNumber(), 3, '0', STR_PAD_LEFT);

			$url = sprintf(Importer::ELECTIONS_ADMINISTRATION_CONSTITUENCY_URL, $stateNo, $constituencyNo);

			$results = HtmlParser::parseResultTableBody($url);

			foreach ($this->electionsAdministrationIgnoreKeys as $ignoreKey) {
				unset($results[$ignoreKey]);
			}

			foreach ($parties as $party) {
				unset($results[$party->getName()]);
			}

			foreach ($results as $name => $votes) {
				if($name=='ÖDP / Familie ..') continue;
				$freeCandidate = new Candidate();
				$freeCandidate->setName($name);
				$this->em->persist($freeCandidate);

				$constituencyCandidacy = $this->factory->createCandidateConstituency($freeCandidate, $constituency);
				$this->em->persist($constituencyCandidacy);

				$this->freeConstituencyCandidateResults[] = array($constituencyCandidacy, $votes);
			}
		}
	}

	private function importResults(array &$data)
	{
		//first results
		//candidates
		//free candidates
		$k=0;
		foreach($this->freeConstituencyCandidateResults as $freeConstituencyCandidateResults) {
			$freeConstituencyCandidate = $freeConstituencyCandidateResults[0];
			$votes = $freeConstituencyCandidateResults[1];

			for($i=0;$i<$votes;$i++) {
				$firstResult = $this->factory->createFirstResult($freeConstituencyCandidate);
				$this->em->persist($firstResult);


				if($k%1000 == 1) {
					$this->em->flush();
					$this->output->writeln("Flushing...");
					$i=0;
				}
				$k++;
			}

		}
		//second results
	}

}
