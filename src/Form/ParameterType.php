<?php

namespace App\Form;

use App\Entity\Parameter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ParameterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('schoolYear', TextType::class ,[
                'label' => ' Année scolaire '
            ])
            ->add('phone', TextType::class ,[
                'label' => ' Téléphone '
            ])
            ->add('address', TextType::class ,[
                'label' => ' Adresse '
            ])
            ->add('email', TextType::class ,[
                'label' => ' E-mail '
            ])
            
            /*->add('programFile', VichFileType::class ,[
                'required' => false ,
                'allow_delete' => true,
                'label' => '  '
            
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Parameter::class,
        ]);
    }
}
