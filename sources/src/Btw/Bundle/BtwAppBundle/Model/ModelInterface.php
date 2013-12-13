<?php

namespace Btw\Bundle\BtwAppBundle\Model;

interface ModelInterface
{

	/**
	 * @param array $data
	 *
	 * @return ModelInterface
	 */
	public static function fromArray(array &$data);

	/**
	 * @return array
	 */
	public function toArray();

}
