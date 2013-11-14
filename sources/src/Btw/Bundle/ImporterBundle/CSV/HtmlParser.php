<?php
namespace Btw\Bundle\ImporterBundle\CSV;

use Symfony\Component\DomCrawler\Crawler;

/**
 *
 * The class handles parsing of HTML.
 * @package Btw\Bundle\ImporterBundle\CSV
 */

class HtmlParser
{

	public static function parseResultTableBody($url)
	{
		$html = file_get_contents($url);

		$crawler = new Crawler($html);

		$resultTableRows = $crawler->filterXPath('//*[@id=\'INHALT\']//table//tbody//tr');

		$result = array();
		foreach ($resultTableRows as $row) {
			$i = 0;
			foreach ($row->childNodes as $rowColumn) {
				if ($i == 0) {
					$type = $rowColumn->nodeValue;
				} else if ($i == 2) {
					$value = (int)(str_replace(".", "", $rowColumn->nodeValue));
				} else if ($i > 2) {
					break;
				}
				$i++;
			}
			$result[$type] = $value;
		}

		return $result;
	}
} 