<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 06.12.13
 * Time: 19:58
 */

namespace Btw\Bundle\BtwAppBundle\Services;


class DummyElectionProvider implements ElectionProviderInterface
{

	public function getElections()
	{
		return array(2005, 2009, 2013);
	}

	public function getResultsFor($year)
	{
		return array(
			array('DIE LINKE', 64,  11, '#fc0204'),
			array('SPD',       193, 38, '#e20019'),
			array('GRÜNE',     63,  9, '#1faf12'),
			array('CDU',       255, 44, '#333333'),
			array('CSU',       56,  6, '#333333'),
		);
	}
}