<?php
namespace Btw\Bundle\ImporterBundle\Import;

use Doctrine\ORM\EntityManager;

/**
 * The importer class loads election data into the database.
 *
 * @package Btw\Bundle\ImporterBundle\Import
 */
class Importer
{

	/** @var  EntityManager */
	private $em;
	/** @var  EntityFactory */
	private $factory;

	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
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
		$this->importConstituencies($demography);
		$this->importParties($results);
		$this->importStateLists($results);
		$this->importCandidates($candidates);
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
		foreach ($data as $row) {
			if ($row[1] < 900 || $row[1] > 920) continue;
			$state = $this->factory->createState($row);
			$this->em->persist($state);
		}
	}

	private function importConstituencies(array $data)
	{
		foreach ($data as $row) {
			if ($row[1] > 900) continue;
			$constituency = $this->factory->createConstituency($row);
			$this->em->persist($constituency);
		}
	}

	private function importParties(array &$data)
	{
		$i = 0;
		foreach ($data[0] as $column) {
			if (empty($column)) continue;
			if ($i++ < 7) continue;
			$party = $this->factory->createParty($column);
			$this->em->persist($party);
		}
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

			$candidate = $this->factory->createCandidate($name, $constituencyNo, $partyAbbr);
			if(is_null($candidate)) continue;

			$this->em->persist($candidate);
		}
	}

	private function importResults(array &$data)
	{
	}

}
