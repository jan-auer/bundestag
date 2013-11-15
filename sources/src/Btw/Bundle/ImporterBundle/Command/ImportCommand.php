<?php
namespace Btw\Bundle\ImporterBundle\Command;

use Btw\Bundle\ImporterBundle\Parser\HtmlParser;
use Btw\Bundle\ImporterBundle\Parser\CsvParser;
use Btw\Bundle\ImporterBundle\Import\Importer;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends ContainerAwareCommand
{

	/** @var  EntityManager */
	private $entityManager;

	protected function configure()
	{
		$this
			->setName('btw:import')
			->setDescription('Imports data and results of an election..')
			->addArgument('election', InputArgument::REQUIRED, 'Path to the election CSV file.')
			->addArgument('demography', InputArgument::REQUIRED, 'Path to the demography CSV file.')
			->addArgument('candidates', InputArgument::REQUIRED, 'Path to the candidates CSV file.')
			->addArgument('results', InputArgument::REQUIRED, 'Path to the results CSV file.')
			->addArgument('partynamemapping', InputArgument::REQUIRED, 'Path to the party-name-mapping CSV file.');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$electionPath = $input->getArgument('election');
		$demographyPath = $input->getArgument('demography');
		$candidatesPath = $input->getArgument('candidates');
		$resultsPath = $input->getArgument('results');
		$partynamemappingPath = $input->getArgument('partynamemapping');

		$election   = CsvParser::parse($electionPath,   true);
		$demography = CsvParser::parse($demographyPath, true);
		$candidates = CsvParser::parse($candidatesPath, true);
		$results    = CsvParser::parse($resultsPath,    false);
		$partynamemapping = CsvParser::parse($partynamemappingPath, false);

		$importer = new Importer($this->getEntityManager(), $output);
		$importer->import($election, $demography, $candidates, $results, $partynamemapping, $output);
	}

	/**
	 * Retrieves a Doctrine EntityManager instance which is configured for the BTW database.
	 * @return EntityManager
	 */
	protected function getEntityManager()
	{
		if ($this->entityManager == null) {
			$doctrine = $this->getContainer()->get('doctrine');
			$this->entityManager = $doctrine->getManager();
		}
		return $this->entityManager;
	}

}
