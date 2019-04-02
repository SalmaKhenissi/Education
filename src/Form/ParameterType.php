<?php

namespace App\Form;

use App\Entity\Parameter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class ParameterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('phone', TextType::class ,[
                'label' => ' Téléphone '
            ])
            ->add('address', TextType::class ,[
                'label' => ' Adresse '
            ])
            ->add('email', TextType::class ,[
                'label' => ' E-mail '
            ])
            ->add('bilingDesc', CKEditorType::class ,[
                'label' => ' Bilinguisme '
            ])
            ->add('extraDesc', CKEditorType::class ,[
                'label' => ' Périscolaire '
            ])
            ->add('helpDesc', CKEditorType::class ,[
                'label' => ' Aide enfant '
            ])
            ->add('programDesc', CKEditorType::class ,[
                'label' => ' Programme '
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Parameter::class,
        ]);
    }
}
