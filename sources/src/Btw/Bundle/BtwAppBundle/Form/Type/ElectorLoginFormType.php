<?php

namespace Btw\Bundle\BtwAppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ElectorLoginFormType extends AbstractType
{

	public function getButtonValue()
	{
		return 'btw_app_vote_ballot';
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('hash', 'text', array(
				'label' => 'Wahlschlüssel:',
			))
			->add('submit', 'submit', array(
				'label' => 'Stimmzettel öffnen',
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
