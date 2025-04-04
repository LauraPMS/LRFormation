<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as SFType;

class FormationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', SFType\DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('nbrHeures', SFType\NumberType::class, [
                'label' => 'Nombre d’heures',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('departement', SFType\TextType::class, [
                'label' => 'Département',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('leProduit', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'libelle',
                'label' => 'Produit',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('pays', SFType\TextType::class, [
                'label' => 'Pays',
                'attr' => ['class' => 'form-control'],
            ]);
    }
    


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }

    
}
