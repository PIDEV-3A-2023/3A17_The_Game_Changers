<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Votre nom','label_attr' => [
                    'style' => 'margin-top: 30px; margin-right:70px;'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le nom ne doit pas dépasser {{ limit }} caractères.'
                    ]),
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Votre prénom','label_attr' => [
                    'style' => 'margin-top: 30px; margin-right:45px;'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le prénom ne doit pas dépasser {{ limit }} caractères.'
                    ]),
                ]
            ])
            ->add('adresse', EmailType::class, [
                'label' => 'Adresse e-mail','label_attr' => [
                    'style' => 'margin-top: 30px;margin-buttom: 60px; margin-right:40px;'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une adresse e-mail.',
                    ]),
                    new Email([
                        'message' => 'Veuillez saisir une adresse e-mail valide.',
                    ]),
                ],
            ])
            ->add('contenu', TextareaType::class, [
                'label' => 'Votre réclamation',
                'attr' => ['rows' => 5],'label_attr' => [
                    'style' => 'margin-top: 50px; margin-right:10px; position:relative ; bottom:10px;'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 500,
                        'maxMessage' => 'Le contenu de la réclamation ne doit pas dépasser {{ limit }} caractères.'
                    ]),
                ]
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
