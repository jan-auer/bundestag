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
		"BE" => "11",
		"RP" => "7",
		"NI" => "3",
		"BY" => "9",
		"NW" => "5",
		"ST" => "15",
		"HE" => "6",
		"HH" => "2",
		"TH" => "16",
		"BW" => "8",
		"HB" => "4",
		"SL" => "10",
		"SH" => "1",
		"BB" => "12",
		"MV" => "13",
		"SN" => "14"
	);

	public static function StateIdForStateAbbr($stateAbbr)
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