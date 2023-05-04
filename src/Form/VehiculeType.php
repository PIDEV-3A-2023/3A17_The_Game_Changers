<?php

namespace App\Form;

use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\ColorType;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque', ChoiceType::class, [
                'choices' => [
                    'IsinWheel' => 'IsinWheel',
                    'NIU' => 'NIU',
                    'Inomile' => 'Inomile',
                    'Xiaomi' => 'Xiaomi',
                    'Evercross EV' => 'Evercross EV',
                    'HITWAY' => 'HITWAY',
                    'SixFox' => 'SixFox',
                    'RCB' => 'RCB',
                    
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please select a brand']),
                ],
            ])
            ->add('vitesseMax', ChoiceType::class, [
                'choices' => [
                    '10klm/h' => '10klm/h',
                    '15klm/h' => '15klm/h',
                    '20klm/h' => '20klm/h',
                    '25klm/h' => '25klm/h',
                    '30klm/h' => '30klm/h',
                    '35klm/h' => '35klm/h',
                    '40klm/h' => '40klm/h',
                    '50klm/h' => '50klm/h',
                    
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please select a brand']),
                ],
            ])
            ->add('chargeMaxsupp', null, [
                'constraints' => [
                    new PositiveOrZero(['message' => 'Max additional charge must be a positive number']),
                    new Range([
                        'min' => 0,
                        'max' => 100,
                        'notInRangeMessage' => 'Max additional charge must be between {{ min }} and {{ max }} %',
                    ]),
                ],
            ])
            ->add('autoBatterie', ChoiceType::class, [
                'choices' => [
                    '20klm/1charge' => '20klm/1charge',
                    '25klm/1charge' => '25klm/1charge',
                    '30klm/1charge' => '30klm/1charge',
                    '40klm/1charge' => '40klm/1charge',
                    '50klm/1charge' => '50klm/1charge',
                   
                    
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please select a brand']),
                ],
            ])
          
           
            ->add('typeVehicule', ChoiceType::class, [
                'choices' => [
                    'Electric Scooter' => 'Electric Scooter',
                    'Electric Bike' => 'Electric Bike',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Please select a vehicle type']),
                ],
            ])


            ->add('couleur', ColorType::class, [
                'label' => 'couleur',
                'required' => false,
                'mapped' => false,
            ])


            ->add('prix', null, [
                'constraints' => [
                    new PositiveOrZero(['message' => 'Price must be a positive number']),
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPEG, PNG or GIF)',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }


}
