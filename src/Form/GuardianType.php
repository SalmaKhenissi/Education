<?php

namespace App\Form;

use App\Entity\Guardian;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class GuardianType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address' , TextType::class , [
                'label' => 'Adresse'
            ])
            ->add('email', EmailType::class , [
                'label' => 'E-mail'
            ])
            ->add('tel', TelType::class , [
                'label' => 'Téléphone'
            ])
            ->add('job', TextType::class , [
                'label' => 'Profession'
            ])
            ->add('cin'  , TextType::class ,[
                'label' => 'Cin'
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
            'data_class' => Guardian::class,
        ]);
    }
}
