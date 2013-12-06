<?php
/**
 * Created by PhpStorm.
 * User: schaefep
 * Date: 06.12.13
 * Time: 19:56
 */

namespace Btw\Bundle\BtwAppBundle\Services;


interface ElectionProviderInterface {

	public function getElections();

	public function getResultsFor($election);
} 