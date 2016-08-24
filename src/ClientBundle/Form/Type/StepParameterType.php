<?php

namespace ClientBundle\Form\Type;

use ClientBundle\Model\StepParameter;
use Monolog\Logger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class StepParameterType
 * @package ClientBundle\Form\Type
 */
class StepParameterType extends AbstractType
{
    /**
     * @var Logger
     */
    protected $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Table' => 'table',
                    'Multiline' => 'multiline'
                ],
                'expanded' => true,
                'attr' => [
                    'class' => 'parameter-type'
                ]
            ])
        ;

        $logger = $this->logger;

        $formModifier = function (FormInterface $form, StepParameter $parameter = null) use ($logger) {
            if ($parameter) {
                if ($parameter->getType() === StepParameter::TYPE_TABLE) {
                    $form->add('keys', CustomCollectionType::class, [
                        'entry_type' => ArrayKeyType::class,
                        'entry_options' => [
                            'attr' => [
                                'class' => 'array-header-item',
                            ],
                            'label' => false
                        ],
                        'label' => false,
                        'attr' => [
                            'class' => 'table-header',
                            'data-prototype-name' => 'tableHeaderName'
                        ],
                        'add_label' => 'Add column',
                        'prototype_name' => 'tableHeaderName'
                    ]);
                    $form->add('values', CustomCollectionType::class, [
                        'entry_type' => CustomCollectionType::class,
                        'entry_options' => [
                            'entry_type' => ArrayKeyType::class,
                            'entry_options' => [
                                'label' => false
                            ],
                            'label' => false,
                            'attr' => [
                                'class' => 'table-body',
                                'data-prototype-name' => 'tableCellName'
                            ],
                            'add_label' => 'Add column',
                            'prototype_name' => 'tableCellName'
                        ],
                        'label' => false,
                        'add_label' => 'Add new line',
                        'remove_label' => 'Remove line',
                        'prototype_name' => 'tableLineName',
                        'attr' => [
                            'data-prototype-name' => 'tableLineName',
                            'class' => 'table-row'
                        ]
                    ]);
                    if ($form->has('content')) {
                        $form->remove('content');
                    }
                } elseif ($parameter->getType() === StepParameter::TYPE_MULTILINE) {
                    $form->add('content', TextareaType::class, [
                        'label' => false,
                        'attr' => [
                            'rows' => 10
                        ]
                    ]);
                    if ($form->has('keys')) {
                        $form->remove('keys');
                    }
                    if ($form->has('values')) {
                        $form->remove('values');
                    }
                }
            }
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $formModifier($event->getForm(), $data);
            }
        );

        $builder->get('type')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getForm()->getData();
                $parameter = new StepParameter();
                if ($data === 'multiline') {
                    $parameter->setType(StepParameter::TYPE_MULTILINE);
                    if ($event->getForm()->getParent()->has('content')) {
                        $parameter->setContent($event->getForm()->getParent()->get('content')->getData());
                    }
                } elseif ($data === 'table') {
                    $parameter->setType(StepParameter::TYPE_TABLE);
                    if ($event->getForm()->getParent()->has('keys')) {
                        $parameter->setKeys($event->getForm()->getParent()->get('keys')->getData());
                    }
                    if ($event->getForm()->getParent()->has('values')) {
                        $parameter->setValues($event->getForm()->getParent()->get('values')->getData());
                    }
                }
                $formModifier($event->getForm()->getParent(), $parameter);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'ClientBundle\Model\StepParameter',
            'label' => false,
            'attr' => [
                'class' => 'step-parameter'
            ]
        ]);
    }
}
