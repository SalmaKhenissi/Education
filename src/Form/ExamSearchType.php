<?php

namespace App\Form;
use App\Entity\Quarter;
use App\Entity\ExamSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType ;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class ExamSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
        ->add('quarter' , EntityType::class , [
            'label' => false,
            'class' => Quarter::class,
            'choice_label' => 'libelle',
            'multiple' => false,
            
        ])
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                    'data_class' => ExamSearch::class,
                    'method' => 'get',
                    'csrf_protection' => false
                 ])
        ;
    }

    public function getBlockPrefix()
    {
        return '';
    }

   
}
