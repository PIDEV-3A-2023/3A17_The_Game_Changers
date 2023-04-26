<?php

namespace App\Form;
use App\Entity\Reclamation;

use App\Entity\SuiviReclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;

class SuiviReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('etatReclamation', ChoiceType::class, [
                'choices' => [
                    'Open' => 'Open',
                    'In Progress' => 'In Progress',
                    'Closed' => 'Closed',
                ],
                'placeholder' => 'Select a state',
                'label' => 'State',
                'required' => true,'label_attr' => [
                    'style' => 'margin-top: 30px;margin-buttom: 60px; margin-right:40px;'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select a state',
                    ]),
                    new Choice([
                        'choices' => [
                            'Open',
                            'In Progress',
                            'Closed',
                        ],
                        'message' => 'Please select a valid state',
                    ]),
                ],
            ])
            ->add('reponseReclamation', TextType::class, [
                'label' => 'Description',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter the description here',
                    'class' => 'form-control',
                    
                ],
                'data' => 'Not treated yet','label_attr' => [
                    'style' => 'margin-top: 30px;margin-buttom: 160px; margin-right:40px;'
                ], // set default value
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the description',
                    ]),
                ],
            ])
            ->add('idReclamation', TextType::class, [
                'label' => 'Reclamation',
                'disabled' => true,
                'data' => isset($options['data']) && $options['data']->getIdReclamation() ? $options['data']->getIdReclamation()->getId().' - '.$options['data']->getIdReclamation()->getContenu() : null,
                'label_attr' => [
                    'style' => 'margin-top: 130px; margin-right:30px; position:relative ; bottom:90px;'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select a reclamation',
                    ]),
                ],
            ])
            
           
            ->add('save', SubmitType::class, [
                'label' => 'Create',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SuiviReclamation::class,
        ]);
    }
}
