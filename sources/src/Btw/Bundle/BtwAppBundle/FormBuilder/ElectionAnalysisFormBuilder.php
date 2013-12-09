<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 07/12/13
 * Time: 18:07
 */

namespace Btw\Bundle\BtwAppBundle\FormBuilder;


use Btw\Bundle\PersistenceBundle\Entity\State;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ElectionAnalysisFormBuilder extends AbstractType
{
	/**
	 * Returns the name of this type.
	 *
	 * @return string The name of this type
	 */
	public function getName()
	{
		return "ElectionAnalysis";
	}


	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$stateChoices = array();
		foreach ($options['states'] as $state) {
			$stateChoices[$state->getId()] = $state->getName();
		}

		$builder->add('state', 'entity', array(
				'class' => 'BtwPersistenceBundle:State',
				'empty_value' => 'Choose a state',
				'choices' => $options['states'],
				'required' => false,
			)
		)->add('constituency', 'entity', array(
					'class' => 'BtwPersistenceBundle:Constituency',
					'empty_value' => 'Choose a state first',
					'choices' => array(),
					'required' => false
				)
			)
			->add('save', 'submit');


		$formModifier = function (FormInterface $form, State $state) {
			$form->add('constituency', 'entity', array(
					'class' => 'BtwPersistenceBundle:Constituency',
					'empty_value' => 'Choose a state first',
					'choices' => $state->getConstituencies(),
					'required' => false
				)
			);
		};

		$builder->addEventListener(
			FormEvents::PRE_SET_DATA,
			function (FormEvent $event) use ($formModifier) {
				// this would be your entity, i.e. SportMeetup
				$model = $event->getData();

				if ($model->getState() != NULL) {
					$formModifier($event->getForm(), $model->getState());
				}
			}
		);

		$builder->get('state')->addEventListener(
			FormEvents::POST_SUBMIT,
			function (FormEvent $event) use ($formModifier) {
				// It's important here to fetch $event->getForm()->getData(), as
				// $event->getData() will get you the client data (that is, the ID)
				$state = $event->getForm()->getData();

				// since we've added the listener to the child, we'll have to pass on
				// the parent to the callback functions!
				$formModifier($event->getForm()->getParent(), $state);
			}
		);

	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Btw\Bundle\BtwAppBundle\Model\ElectionAnalysisModel',
			'year' => 'integer',
			'states' => 'BtwPersistenceBundle:State',
		));
	}


}