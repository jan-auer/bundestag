<?php

namespace Btw\Bundle\BtwAppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LocationLoginFormType extends AbstractType
{
	private $election;

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$this->election = $options['data']['election'];
		$builder
			->add('constituency', 'entity', array(
				'label_render' => false,
				'class' => 'Btw\Bundle\PersistenceBundle\Entity\Constituency',
				'query_builder' => function (EntityRepository $repo) {
						$qb = $repo
							->createQueryBuilder('c')
							->where('c.election = :election')
							->orderBy('c.number');

						$qb->setParameter('election', $this->election);
						return $qb;
					},
			));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'render_fieldset' => false,
			'show_legend' => false,
		));
	}

	public function getName()
	{
		return 'BTW_Elector_Register';
	}

}
