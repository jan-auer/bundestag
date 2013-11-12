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
	 * @param array $election   An array containing the collection number and date.
	 * @param array $demography An array containing all states and constituencies.
	 * @param array $candidates An array containing all candidates and their parties.
	 * @param array $results    An array containing aggregated results of the election.
	 */
	public function import(array $election, array $demography, array $candidates, array $results)
	{
		$this->factory = new EntityFactory();

		$this->importElection($election);
		$this->importDemography($demography);
		$this->importCandidates($candidates);
		$this->importResults($results);

		$this->em->flush();
	}

	private function importElection(array $data)
	{
		$election = $this->factory->createElection($data[0]);
		$this->em->persist($election);
	}

	private function importDemography(array $data)
	{
		foreach ($data as $row) {
			if ($row[1] < 900 || $row[1] > 920) continue;
			$state = $this->factory->createState($row);
			$this->em->persist($state);
		}
	}

	private function importCandidates(array $data)
	{
	}

	private function importResults(array $data)
	{
	}

}
