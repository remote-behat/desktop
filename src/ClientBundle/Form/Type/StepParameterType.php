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
                    $form->add('keys', TableLineType::class, [
                        'header' => true,
                        'entry_options' => [
                            'to_remove_column' => true
                        ]
                    ]);
                    $form->add('values', TableLineCollectionType::class);
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
                $form = $event->getForm();
                $formModifier($form, $data);
            }
        );

        $builder->get('type')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $parameter = new StepParameter();
                $parameter->setType($data);
                if ($data === StepParameter::TYPE_MULTILINE) {
                    if ($event->getForm()->getParent()->has('content')) {
                        $parameter->setContent($event->getForm()->getParent()->get('content')->getData());
                    }
                } elseif ($data === StepParameter::TYPE_TABLE) {
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
