<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamationSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
        ->add('nom', TextType::class, [
            'required' => false,
            'label' => 'Nom'
        ])
        ->add('prenom', TextType::class, [
            'required' => false,
            'label' => 'Prénom'
        ])
        ->add('adresse', TextType::class, [
            'required' => false,
            'label' => 'Adresse e-mail'
        ])
        ->add('etat', ChoiceType::class, [
            'required' => false,
            'label' => 'Etat',
        ])
        ->add('rechercher', SubmitType::class, [
            'label' => 'Rechercher',
            'attr' => ['class' => 'btn btn-primary', 'id' => 'search_button']
        ])
        ->setMethod('POST')
        ->setAction($options['search_route'])
        ->setAttriubutes([
            'class' => 'form-search', 
            'id' => 'searchresultat' // add the id attribute here
        ]);
    
         
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
            'search_route' => '',
        ]);
    }
}
