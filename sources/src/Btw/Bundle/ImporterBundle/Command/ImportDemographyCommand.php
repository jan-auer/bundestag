<?php
namespace Btw\Bundle\ImporterBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportDemographyCommand extends ContainerAwareCommand
{

	protected function configure()
	{
		$this
			->setName('btw:import:demography')
			->setDescription('Imports demography data, like countries, constituencies and stuff.')
			->addArgument('file', InputArgument::REQUIRED, 'Path to the CSV file with all configurations.');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$file = $input->getArgument('file');
		$data = $this->parseCSV($file, true);
	}

	private function parseCSV($path, $ignoreFirstLine)
	{
		$path = realpath($path);
		$rows = array();

		if (($handle = fopen($path, 'r')) === false)
			return $rows;

		$i = 0;
		while (($data = fgetcsv($handle, null, ';')) !== false) {
			if ($ignoreFirstLine && $i++ === 0)
				continue;

			$rows[] = $data;
		}

		fclose($handle);
		return $rows;
	}

}
