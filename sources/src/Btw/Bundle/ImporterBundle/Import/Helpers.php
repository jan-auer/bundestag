<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 15.11.13
 * Time: 16:49
 */

namespace Btw\Bundle\ImporterBundle\Import;


class Helpers
{
	private static $stateNameAbbrMap = array(
		"BE" => "Berlin",
		"RP" => "Rheinland-Pfalz",
		"NI" => "Niedersachsen",
		"BY" => "Bayern",
		"NW" => "Nordrhein-Westfalen",
		"ST" => "Sachsen-Anhalt",
		"HE" => "Hessen",
		"HH" => "Hamburg",
		"TH" => "Thüringen",
		"BW" => "Baden-Württemberg",
		"HB" => "Bremen",
		"SL" => "Saarland",
		"SH" => "Schleswig-Holstein",
		"BB" => "Brandenburg",
		"MV" => "Mecklenburg-Vorpommern",
		"SN" => "Sachsen"
	);

	public static function StateNameForStateAbbr($stateAbbr)
	{
		return Helpers::$stateNameAbbrMap[$stateAbbr];
	}

	public static function FullPartyNameForAbbreviation($partyAbbr, $partynamemapping)
	{
		foreach ($partynamemapping as $partyname) {
			if ($partyname[0] == $partyAbbr) {
				return $partyname[1];
			}
		}

		return $partyAbbr;
	}
} 