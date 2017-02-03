<?php

namespace ClientBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableLineCollectionType extends AbstractType
{
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
            'entry_type' => TableLineType::class,
            'label' => false,
            'add_label' => 'Add new line',
            'remove_label' => 'Remove line',
            'prototype_name' => 'tableLineName',
            'attr' => [
                'data-prototype-name' => 'tableLineName',
                'class' => 'table-row'
            ]
        ]);
    }
}
