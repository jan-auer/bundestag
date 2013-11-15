<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 15.11.13
 * Time: 20:48
 */

namespace Btw\Bundle\ImporterBundle\VoteExport;

/**
 * Class FirstVotesExporter
 * @package Btw\Bundle\ImporterBundle\VoteExport
 *
 * Hint: the resulting CSV file may be imported to PSQL using the following statement:
 * COPY first_result(candidate_id) FROM <path>\<election-date>fst.csv;
 *
 * Anyhow, pg_bulkload should be faster..
 */
class FirstVotesExporter extends VotesExporter {
	public function append($candidateId, $count)
	{
		for ($i = 0; $i < $count; $i++) {
			$this->data .= $candidateId . "\n";
		}
	}
} 