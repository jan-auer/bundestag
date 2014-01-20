<?php

namespace Btw\Bundle\BtwAppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * A login form for election helpers to choose their constituency.
 */
class LocationLoginFormType extends AbstractType
{

	/** @var string */
	private $election;

	/**
	 * @inheritdoc
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$this->election = $options['data']['election'];
		$builder
			->add('constituency', 'entity', array(
				'label_render'  => false,
				'class'         => 'Btw\Bundle\PersistenceBundle\Entity\Constituency',
				'query_builder' => array($this, 'createElectionQuery'),
			));
	}

	/**
	 * @inheritdoc
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'render_fieldset' => false,
			'show_legend'     => false,
		));
	}

	/**
	 * @inheritdoc
	 */
	public function getName()
	{
		return 'BTW_Elector_Register';
	}

	/**
	 * Creates a query which fetches all constituencies for the configured election.
	 *
	 * @param EntityRepository $repository The repository which will execute the query.
	 *
	 * @return QueryBuilder The query builder which creates the query.
	 */
	private function createElectionQuery(EntityRepository $repository)
	{
		return $repository
			->createQueryBuilder('c')
			->where('c.election = :election')
			->orderBy('c.number')
			->setParameter('election', $this->election);
	}

}
