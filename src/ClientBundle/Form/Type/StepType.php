<?php

namespace ClientBundle\Form\Type;

use ClientBundle\Model\Step;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class StepType
 * @package ClientBundle\Form\Type
 */
class StepType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Given' => Step::TYPE_GIVEN,
                    'When' => Step::TYPE_WHEN,
                    'Then' => Step::TYPE_THEN,
                    'And' => Step::TYPE_AND
                ]
            ])
            ->add('content', null, ['label' => false])
            ->add('parameters', CustomCollectionType::class, [
                'entry_type' => StepParameterType::class,
                'label' => false,
                'add_label' => 'Add parameter',
                'prototype_name' => 'parameterName',
                'attr' => [
                    'data-prototype-name' => 'parameterName'
                ]
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'ClientBundle\Model\Step',
            'label' => false,
            'attr' => [
                'class' => 'step'
            ]
        ]);
    }
}
