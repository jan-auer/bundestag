<?php

namespace Btw\Bundle\BtwAppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ElectorLoginFormType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('hash', 'text', array(
				'label_render' => false,
				'attr'         => array(
					'autocomplete' => "off",
				),
			))
			->add('submit', 'submit', array(
				'label' => 'Stimmzettel öffnen',
				'attr'  => array('class' => 'btn-default')
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
		return 'BTW_Elector_Login';
	}

}
