<?php

namespace App\Form;

use App\Entity\Level;
use App\Entity\Specialty;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SpecialtyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class ,[
                'label' => ' Libellé '
            ])
            ->add('shortcut', TextType::class ,[
                'label' => ' Raccourci '
            ])
            ->add('levels', EntityType::class , [
                'class' => 'App\Entity\Level' ,
                'multiple' => true ,
                 'label' => 'Niveaux',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Specialty::class,
        ]);
    }
}
