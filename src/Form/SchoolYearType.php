<?php

namespace App\Form;

use App\Form\QuarterType;
use App\Entity\SchoolYear;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SchoolYearType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startAt',BirthdayType::class, [
                'label' => ' Début',
                 'widget' => 'single_text'
            ])
            ->add('finishAt', BirthdayType::class, [
                'label' => 'Fin',
                'widget' => 'single_text' 
            ])
            ->add('quarters' , CollectionType::class,[
                'entry_type' => QuarterType::class ,
                'entry_options' => [
                    'label' => false                ],
                'by_reference' => false,
                'allow_add' => true ,
                'allow_delete' => true ,
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SchoolYear::class,
        ]);
    }
}
