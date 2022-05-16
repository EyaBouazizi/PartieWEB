<?php

namespace App\Form;
use App\Entity\Livre;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('titre')
            ->add('auteur')
            ->add('image',FileType::class,[
                'mapped' => false
            ])
            
            ->add('description',TextareaType::Class)
            ->add('idCategorie')
            ->add('text_livre',TextareaType::Class)
            ->add('ajouter', SubmitType::class)
            
           
           
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            
        ]);
    }
}
