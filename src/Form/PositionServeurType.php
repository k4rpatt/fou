<?php

namespace App\Form;

use App\Entity\Alliance;
use App\Entity\Position;
use App\Entity\Serveur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PositionServeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('posX')
            ->add('posY')
//            ->add("moment", DateTimeType::class, [
//                'placeholder' => [
//                    'day' => 'Day',
//                    'hour' => 'Hour', 'minute' => 'Minute', 'second' => 'Second',
//                ],
//            ])
                ->add("duree", DateIntervalType::class, [
                'widget'      => 'integer', // render a text field for each part
                // 'input'    => 'string',  // if you want the field to return a ISO 8601 string back to you

                // customize which text boxes are shown
                'with_years'  => false,
                'with_months' => false,
                'with_days'   => true,
                'with_hours'  => true,
                'labels' => [

                    'days' => 'jours',
                    'hours' => 'heures',
                    'minutes' => 'minutes',
                    'seconds' => 'secondes',
                ]
            ] )
            ->add('alliance', EntityType::class, [
                'class' => Alliance::class,
                'choice_label' => 'nom',
            ])
//            ->add('serveur', EntityType::class, [
//                'class' => Serveur::class,
//                'choice_label' => 'numero',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Position::class,
        ]);
    }
}
