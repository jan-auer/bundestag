<?php

namespace Btw\Bundle\BtwAppBundle\Services;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Base class for all providers. These classes use a Doctrine {@link EntityRepository}
 * to load data from the database and compute it in a certain way, so it can be used
 * by one or more controller actions.
 */
class AbstractProvider
{

	/** @var  EntityManager */
	protected $em;

	/**
	 * Creates a new Provider. Be sure to call parent::__construct in every subclass.
	 *
	 * @param EntityManager $entityManager The doctrine entity manager to build queris.
	 */
	function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	/**
	 * Creates a new prepared query.
	 *
	 * @param string $query The query SQL.
	 *
	 * @return Statement The prepared statement, ready to bind parameters.
	 */
	protected function prepareQuery($query)
	{
		$connection = $this->em->getConnection();
		return $connection->prepare($query);
	}

	/**
	 * Executes the given prepared statement on the database.
	 *
	 * @param Statement $query A prepared statement with bound parameters.
	 *
	 * @return array An array containing the statement results.
	 */
	protected function executeQuery(Statement $query)
	{
		$query->execute();
		return $query->fetchAll();
	}

	/**
	 * Executes the given prepared statement on the database and pipes the
	 * response objects through the given mapper callback.
	 *
	 * @param Statement $query  A prepared statement with bound parameters.
	 * @param Callable  $mapper A callback to transform the response arrays to models.
	 *
	 * @return array An array containing all mapped result rows.
	 */
	protected function executeMappedQuery(Statement $query, $mapper)
	{
		$query->execute();
		return array_map($mapper, $query->fetchAll());
	}

	/**
	 * Returns a Doctrine {@link EntityManager} instance.
	 * @return EntityManager
	 */
	protected function getEntityManager()
	{
		return $this->em;
	}

	/**
	 * Returns a Doctrine {@link EntityRepository} for the given entity.
	 *
	 * @param string $entityName The name of the entity class.
	 *
	 * @return EntityRepository
	 */
	protected function getRepository($entityName)
	{
		return $this->em->getRepository('BtwPersistenceBundle:' . $entityName);
	}

	/**
	 * Starts a new transaction.
	 */
	protected function beginTransaction()
	{
		$this->em->beginTransaction();
	}

	/**
	 * Commits the current running transaction.
	 *
	 * @throws DBALException In case of a failed commit.
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

}
