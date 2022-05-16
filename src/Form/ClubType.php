<?php

namespace App\Form;

use App\Entity\Club;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_club')
           /* ->add('date_creation')*/
            /*->add('date_creation', DateTimeType::class, array(
          'input' => 'datetime_immutable',
                ))*/
            ->add('club_owner')
            ->add('imageclb', FileType::class
                , array('data_class' => null,'required' => false))
            ->add('access', ChoiceType::class, [
                'choices' => [
                    'public' => true,
                    'prive' => false
                ]
            ]);

           /* ->add('access', ChoiceType::class, array(
                'choices' => array(
                    'public' => 1,
                    'privé' => 2
                ),
                'label' => 'Type Club',
                'required' => true,

            ));*/

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}
