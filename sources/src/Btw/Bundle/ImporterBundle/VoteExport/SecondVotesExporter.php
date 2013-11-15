<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 15.11.13
 * Time: 20:48
 */

namespace Btw\Bundle\ImporterBundle\VoteExport;


class SecondVotesExporter extends VotesExporter
{

	public function append($stateListId, $constituencyId, $count)
	{
		for ($i = 0; $i < $count; $i++) {
			$this->data .= $stateListId . ';' . $constituencyId . "\n";
		}
	}
} 