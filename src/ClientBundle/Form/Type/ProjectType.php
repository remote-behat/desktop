<?php

namespace ClientBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProjectType
 * @package ClientBundle\Form\Type
 */
class ProjectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Project name'
                ]
            ])
            ->add('installationRequirements', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Installation requirements'
                ]
            ])
            ->add('testingRootDir', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Testing root directory, from where Behat should be launched'
                ]
            ])
            ->add('featuresRelativePath', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Relative features location from testing root dir'
                ]
            ])
            ->add('behatExe', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Relative path for Behat executable'
                ]
            ])
            ->add('submit', SubmitType::class, ['label' => false])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'ClientBundle\Entity\Project'
        ]);
    }
}
