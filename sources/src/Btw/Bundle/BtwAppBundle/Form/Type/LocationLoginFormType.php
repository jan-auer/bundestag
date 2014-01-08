<?php

namespace Btw\Bundle\BtwAppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LocationLoginFormType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('constituency', 'entity', array(
				'label_render'  => false,
				'class'         => 'Btw\Bundle\PersistenceBundle\Entity\Constituency',
				'query_builder' => function (EntityRepository $repo) {
						return $repo
							->createQueryBuilder('c')
							->orderBy('c.number');
					},
			));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'render_fieldset' => false,
			'show_legend'     => false,
		));
	}

	public function getName()
	{
		return 'BTW_Elector_Register';
	}

}
