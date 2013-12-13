<?php


namespace Btw\Bundle\BtwAppBundle\Services;

use Doctrine\DBAL\Driver\Statement;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class AbstractProvider
{
	/** @var  EntityManager */
	protected $em;

	/**
	 * @param EntityManager $entityManager
	 */
	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	/**
	 * @param string $query
	 *
	 * @return Statement
	 */
	protected function prepareQuery($query)
	{
		$connection = $this->em->getConnection();
		return $connection->prepare($query);
	}

	/**
	 * @param Statement $query
	 * @param Callable $mapper
	 *
	 * @return array
	 */
	protected function executeQuery(Statement $query, $mapper)
	{
		$query->execute();
		return array_map($mapper, $query->fetchAll());
	}

	/**
	 * @return EntityManager
	 */
	protected function getEntityManager()
	{
		return $this->em;
	}

	/**
	 * @param string $entityName
	 *
	 * @return EntityRepository
	 */
	protected function getRepository($entityName)
	{
		return $this->em->getRepository($entityName);
	}

}
