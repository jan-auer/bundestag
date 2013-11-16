<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 15.11.13
 * Time: 20:49
 */

namespace Btw\Bundle\ImporterBundle\VoteExport;


abstract class VotesExporter
{

	protected $handle;

	protected  $data;

	public function open($electionDate, $targetDir, $isFirstResult)
	{
		$this->data = "";

		if ($isFirstResult == true) $appendix = "fst";
		else $appendix = "snd";

		$filename = $targetDir . $electionDate->format('Y-m-d_H-i-s');
		$file = $filename . $appendix . ".csv";

		if (!file_exists($file)) {
			$this->handle = fopen($file, 'w');
			fclose($this->handle);
		}
		$this->handle = fopen($file, 'a');
	}

	public function close()
	{
		fwrite($this->handle, $this->data);
		fclose($this->handle);
		$this->data = "";
	}
} 