<?php

namespace App\Form;

use App\Entity\Joueur;
use App\Entity\Train;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('depart', null, [
                'widget' => 'single_text',
            ])
            ->add('conducteur', EntityType::class, [
                'class' => Joueur::class,
                'choice_label' => 'nom',
            ])
            ->add('passager1', EntityType::class, [
                'class' => Joueur::class,
                'choice_label' => 'nom',
            ])
            ->add('passager2', EntityType::class, [
                'class' => Joueur::class,
                'choice_label' => 'nom',
            ])
            ->add('passager3', EntityType::class, [
                'class' => Joueur::class,
                'choice_label' => 'nom',
            ])
            ->add('passager4', EntityType::class, [
                'class' => Joueur::class,
                'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Train::class,
        ]);
    }
}
