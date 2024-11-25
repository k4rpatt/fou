<?php

namespace App\Form;

use App\Entity\Joueur;
use App\Entity\Progression;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgressionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('dateProgression', null, [
//                'widget' => 'single_text',
//            ])
            ->add('PC_tank')
            ->add('PC_avion')
            ->add('PC_missile')
//            ->add('joueur', EntityType::class, [
//                'class' => Joueur::class,
//                'choice_label' => 'id',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Progression::class,
        ]);
    }
}
