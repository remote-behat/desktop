<?php

namespace ClientBundle\Form\Type;

use ClientBundle\Model\Scenario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ScenarioType
 * @package ClientBundle\Form\Type
 */
class ScenarioType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Background' => Scenario::TYPE_BACKGROUND,
                    'Scenario' => Scenario::TYPE_SCENARIO
                ],
                'label' => false
            ])
            ->add('name', null, ['label' => false])
            ->add('steps', CustomCollectionType::class, [
                'entry_type' => StepType::class,
                'attr' => [
                    'class' => 'steps',
                    'data-prototype-name' => 'stepName'
                ],
                'add_label' => 'Add step',
                'remove_label' => 'Remove step',
                'label' => false,
                'prototype_name' => 'stepName',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'ClientBundle\Model\Scenario',
            'label' => false,
            'attr' => [
                'class' => 'scenario'
            ]
        ]);
    }
}
