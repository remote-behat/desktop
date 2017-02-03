<?php

namespace ClientBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableLineType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['header'] = $options['header'];
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return CustomCollectionType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'entry_type' => ArrayKeyType::class,
            'entry_options' => [
                'label' => false
            ],
            'label' => false,
            'attr' => [
                'class' => null
            ],
            'header' => false
        ]);
    }
}
