<?php

namespace Btw\Bundle\BtwAppBundle\Model;

/**
 * A Model is a data transfer object which is used to return structural data
 * from a service and then display it in the view.
 *
 * This interface has to be implemented by all model classes.
 */
interface ModelInterface
{

	/**
	 * Creates a new model and populates it with the given data.
	 *
	 * @param array $data A serialized array containing model data.
	 *
	 * @return ModelInterface The new
	 */
	public static function fromArray(array &$data);

	/**
	 * Converts this model into a plain array.
	 * @return array A serialized array suitable for JSON encoding.
	 */
	public function toArray();

}
