<?php
namespace Btw\Bundle\ImporterBundle\Parser;

/**
 * The Parser class handles CSV formatted files.
 *
 * @package Btw\Bundle\ImporterBundle\Parser
 */
class CsvParser
{

	const COLUMN_SEPARATOR = ';';

	/**
	 * Opens the specified file and converts its contents to a PHP array structure.
	 * All lines are split by the ';' delimiter.
	 *
	 * @param string $path            The path to the CSV file.
	 * @param bool   $ignoreFirstLine If true, the parser will not parse the first line.
	 *
	 * @return array An array containing all contents of the CSV.
	 */
	public static function parse($path, $ignoreFirstLine = false)
	{
		$path = realpath($path);
		$rows = array();

		if (($handle = fopen($path, 'r')) === false)
			return $rows;

		$i = 0;
		while (($data = fgetcsv($handle, null, self::COLUMN_SEPARATOR)) !== false) {
			if ($ignoreFirstLine && $i++ === 0)
				continue;

			$rows[] = $data;
		}

		fclose($handle);
		return $rows;
	}

}
