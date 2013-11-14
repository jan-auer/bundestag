<?php
namespace Btw\Bundle\ImporterBundle\Command;

use Btw\Bundle\ImporterBundle\CSV\HtmlParser;
use Btw\Bundle\ImporterBundle\CSV\Parser;
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
			->addArgument('election',   InputArgument::REQUIRED, 'Path to the election CSV file.')
			->addArgument('demography', InputArgument::REQUIRED, 'Path to the demography CSV file.')
			->addArgument('candidates', InputArgument::REQUIRED, 'Path to the candidates CSV file.')
			->addArgument('results',    InputArgument::REQUIRED, 'Path to the results CSV file.');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$electionPath   = $input->getArgument('election');
		$demographyPath = $input->getArgument('demography');
		$candidatesPath = $input->getArgument('candidates');
		$resultsPath    = $input->getArgument('results');

		$election   = Parser::parse($electionPath,   true);
		$demography = Parser::parse($demographyPath, true);
		$candidates = Parser::parse($candidatesPath, true);
		$results    = Parser::parse($resultsPath,    false);

		$importer = new Importer($this->getEntityManager());
		$importer->import($election, $demography, $candidates, $results);
	}

	/**
	 * Retrieves a Doctrine EntityManager instance which is configured for the BTW database.
	 * @return EntityManager
	 */
	protected function getEntityManager() {
		if ($this->entityManager == null) {
			$doctrine = $this->getContainer()->get('doctrine');
			$this->entityManager = $doctrine->getManager();
		}
		return $this->entityManager;
	}

}
