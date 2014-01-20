<?php

namespace Btw\Bundle\BtwAppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * A form to register a new voter.
 */
class LocationRegisterFormType extends AbstractType
{

	/**
	 * @inheritdoc
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('identityNumber', 'text', array(
				'label' => 'Personalausweisnummer',
				'attr'  => array('autocomplete' => "off"),
			))
			->add('submit', 'submit', array(
				'label' => 'Wähler anlegen',
				'attr'  => array('class' => 'btn-default'),
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
			'horizontal'      => true,
		));
	}

	/**
	 * @inheritdoc
	 */
	public function getName()
	{
		return 'BTW_Elector_Register';
	}

}
