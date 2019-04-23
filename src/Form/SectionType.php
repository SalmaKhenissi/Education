<?php

namespace App\Form;

use App\Entity\Level;
use App\Entity\Specialty;
use App\Entity\Section;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            
            ->add('number'  , TextType::class ,[
                'label' => ' Numéro '
            ])
            ->add('level' , EntityType::class , [
                'class' => 'App\Entity\Level' ,
                'multiple' => false ,
                 'label' => 'Niveaux',
            ])
                                                                                                                                                     
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
        ]);
    }

}
