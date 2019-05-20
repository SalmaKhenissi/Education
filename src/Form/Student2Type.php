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
use Symfony\Component\Form\Extension\Core\Type\TextType;

class Student2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
            
    }
    public function getParent()
    {
        return User2Type::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
