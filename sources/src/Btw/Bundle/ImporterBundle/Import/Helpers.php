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
	private static $stateAbbrIdMap = array(
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
		return Helpers::$stateAbbrIdMap[$stateAbbr];
	}

	public static function FullPartyNameForAbbreviation($partyAbbr, $partyMetadatas)
	{
		foreach ($partyMetadatas as $partyMetadata) {
			if ($partyMetadata[0] == $partyAbbr) {
				return $partyMetadata[1];
			}
		}

		return $partyAbbr;
	}

	public static function colorForPartyAbbr($partyAbbr, $partyMetadatas)
	{
		foreach ($partyMetadatas as $partyMetadata) {
			if ($partyMetadata[0] == $partyAbbr) {
				return $partyMetadata[2];
			}
		}

		return null;
	}
}