<?php

namespace App\Form;
use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\LessThanOrEqual([
                        'propertyPath' => 'parent.all[dateFin].data',
                        'message' => 'La date de début doit être inférieure ou égale à la date de fin.',
                    ]),
                ],
                'widget' => 'single_text',
                'data' => new \DateTime('2023-01-01'), // date par défaut avec année 2023
            ])
            ->add('dateFin', DateType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'widget' => 'single_text',
                'data' => new \DateTime('2023-01-01'), // date par défaut avec année 2023
            ])
         ->add('montant', null, [
    'attr' => [
        'id' => 'montant-field'
    ]
])

        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}