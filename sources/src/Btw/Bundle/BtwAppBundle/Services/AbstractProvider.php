<?php


namespace Btw\Bundle\BtwAppBundle\Services;

use Doctrine\DBAL\DBALException;
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
	 * @param Statement $query
	 *
	 * @return boolean
	 */
	protected function executeUpdateQuery(Statement $query)
	{
		return $query->execute();
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
		return $this->em->getRepository('BtwPersistenceBundle:' . $entityName);
	}

	/**
	 * Begins a new transaction
	 */
	protected function beginTransaction()
	{
		$this->em->beginTransaction();
	}

	/**
	 * Commits the current running transaction.
	 * @throws Exception In case of failed commit
	 */
	protected function commit()
	{
		try {
			$this->em->commit();
		} catch (DBALException $e) {
			$this->rollback();
			throw $e;
		}
	}

	/**
	 * Aborts the current running transaction.
	 */
	protected function rollback()
	{
		$this->em->rollback();
	}

	/**
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	protected function createQueryBulder() {
		return $this->em->createQueryBuilder();
	}
}
