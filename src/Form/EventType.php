<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class ,[
                'label' => ' Titre '
            ])
            ->add('shortDescription',CKEditorType::class ,[
                'label' => ' Courte Description '
            ])
            ->add('longDescription',CKEditorType::class ,[
                'label' => ' Longue Description '
            ])
            ->add('time',DateType::class,[
                'label' => ' Temps ',
                'widget' => 'single_text' 
            ])
            ->add('location',TextType::class ,[
                'label' => ' Location '
            ])
            ->add('imageFile', VichImageType::class , [
                'required' => false ,
                'label' => 'Image'
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
