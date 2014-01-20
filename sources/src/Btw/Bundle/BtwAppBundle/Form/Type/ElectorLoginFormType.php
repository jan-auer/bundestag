<?php

namespace Btw\Bundle\BtwAppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * A login form for voters before they are allowed to see the ballot.
 */
class ElectorLoginFormType extends AbstractType
{

	/**
	 * @inheritdoc
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('hash', 'text', array(
				'label_render' => false,
				'attr'         => array('autocomplete' => "off"),
			))
			->add('submit', 'submit', array(
				'label' => 'Stimmzettel öffnen',
				'attr'  => array('class' => 'btn-default')
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
		return 'BTW_Elector_Login';
	}

}
