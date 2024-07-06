<?php

namespace App\Form;

use App\Entity\Alliance;
use App\Entity\Position;
use App\Entity\Serveur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PositionServeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('posX')
            ->add('posY')
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
