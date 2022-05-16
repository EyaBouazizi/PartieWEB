<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Evenement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('event_name')
            ->add('description')
            ->add('event_date',DateTimeType::class)
            /*->add('event_date', DateTimeType::class, [
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                    'hour' => 'Hour', 'minute' => 'Minute', 'second' => 'Second',
                ],
            ])*/
           /* ->add('startingTime', TimeType::class, [
                'input'  => 'timestamp',
                'widget' => 'choice',
            ])
            ->add('endingTime', TimeType::class, [
                'input'  => 'timestamp',
                'widget' => 'choice',
            ])*/
            ->add('image', FileType::class, array('data_class' => null,'required' => false))
          /* ->add('club',EntityType::class,[
                'class'=>Club::class,
                'choice_label'=>'nom_club',
                'placeholder' => 'Choisir un club ',
                'expanded'=>false,
                'multiple'=>false
            ]) */
            ->add('nbrparticiMax')
            ->add('adresse',TextType::class)


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
