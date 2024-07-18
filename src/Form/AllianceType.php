<?php

namespace App\Form;

use App\Entity\Alliance;
use App\Entity\Serveur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AllianceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('zone', ChoiceType::class, [
                'choices'  => [
                    'EU' => "Europe",
                    'US' => "Amérique",
                    'Asie' => "Asie"
                ]])
            ->add('couleur', ColorType::class)
            ->add('serveur', EntityType::class, [
                'class' => Serveur::class,
                'choice_label' => 'numero',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Alliance::class,
        ]);
    }
}
