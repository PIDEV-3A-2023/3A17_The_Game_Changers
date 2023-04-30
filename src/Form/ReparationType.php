<?php

namespace App\Form;

use App\Entity\Reparation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReparationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setAttributes([
                'novalidate' => 'novalidate'
            ])
            ->add('Description_Panne')
            ->add('Etat', ChoiceType::class, [
                'choices' => [
                    'reparé' => true,
                    'non reparé' => false,
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Etat',])
            ->add('Date_rep')
            ->add('Date_declaration')
            ->add('Date_recuperation')
            ->add('id_veh')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reparation::class,
        ]);
    }
}
