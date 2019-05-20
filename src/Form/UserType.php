<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName' , TextType::class ,[
                'label' => ' Prénom'
                ])
            ->add('lastName' , TextType::class , [
                'label' => ' Nom'
            ])
            ->add('sexe' , ChoiceType::class , [
                'choices' => $this->getSexeChoices() ,
                'multiple'=>false,
                'expanded'=>true
            ])
            ->add('birthDate',BirthdayType::class, [
                'label' => ' Date de naissance ', 'widget' => 'single_text'
            ])
            ->add('birthplace',TextType::class, [
                'label' => ' Lieu de naissance '
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
    private function getSexeChoices()
    {
        $choices = User::SEXE ;
        $output = [];
        foreach ($choices as $k => $v)
        {
            $output[$v] = $k ;
        }
        return $output ;
    }
}
