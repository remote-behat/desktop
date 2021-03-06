<?php

namespace ClientBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FeatureType
 * @package ClientBundle\Form\Type
 */
class FeatureType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('scenarios', CustomCollectionType::class, [
                'entry_type' => ScenarioType::class,
                'label' => false,
                'add_label' => 'Add scenario',
                'remove_label' => 'Remove scenario',
                'prototype_name' => 'scenarioName',
                'attr' => [
                    'data-prototype-name' => 'scenarioName',
                    'class' => 'scenarios'
                ]
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'ClientBundle\Model\Feature',
            'csrf_protection' => false
        ]);
    }
}
