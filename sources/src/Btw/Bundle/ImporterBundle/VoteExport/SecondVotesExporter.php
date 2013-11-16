<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 15.11.13
 * Time: 20:48
 */

namespace Btw\Bundle\ImporterBundle\VoteExport;

/**
 * Class SecondVotesExporter
 * @package Btw\Bundle\ImporterBundle\VoteExport
 *
 * Hint: the resulting CSV file may be imported to PSQL using the following statement:
 * COPY second_result(state_list_id, constituency_id) FROM <path>\<election-date>fst.csv;
 *
 * Anyhow, pg_bulkload should be faster..
 */
class SecondVotesExporter extends VotesExporter
{

	public function append($stateListId, $constituencyId, $count)
	{
		for ($i = 0; $i < $count; $i++) {
			$this->data .= $stateListId . ';' . $constituencyId . "\n";
		}
	}
} 