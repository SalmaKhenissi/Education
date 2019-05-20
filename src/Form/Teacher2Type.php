<?php

namespace App\Form;

use App\Form\UserType;
use App\Entity\Teacher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class Teacher2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('email', EmailType::class ,[
                'label' => 'E-mail'
            ])
            ->add('tel', TelType::class ,[
                'label' => 'Téléphone'
            ])
            ->add('cin'  , TextType::class ,[
                'label' => 'Cin'
            ])
            ->add('address' , TextType::class , [
                'label' => 'Adresse'
            ])
            ;
            
    }
    public function getParent()
    {
        return User2Type::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Teacher::class,
        ]);
    }
}
