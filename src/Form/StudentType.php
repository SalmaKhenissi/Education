<?php

namespace App\Form;

use App\Form\UserType;
use App\Entity\Section;
use App\Entity\Student;
use App\Entity\Guardian;
use App\Form\GuardianType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('section' ,
                   EntityType::class , [
                   'class' => Section::class,
                   'multiple' => false  ,
                
                    'label' => 'Classe'
            ])
            
        ;
            
    }
    public function getParent()
    {
        return UserType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
