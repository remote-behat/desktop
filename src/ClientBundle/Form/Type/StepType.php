<?php

namespace ClientBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
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
            ->add('content', null, ['label' => false])
            ->add('parameters', CustomCollectionType::class, [
                'entry_type' => StepParameterType::class,
                'label' => false,
                'add_label' => 'Add parameter'
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
