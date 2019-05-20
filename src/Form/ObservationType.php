<?php

namespace App\Form;

use App\Entity\Section;
use App\Entity\Observation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ObservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sections = $options['sections'];
        $builder
            ->add('description',TextareaType::class,[
                'label' => ' Description '
            ])
            ->add('sections',EntityType::class , [
                'class' => Section::class,
                'choices' => $sections ,
                'multiple' => true  ,
                'label' => 'Classes'
         ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                         'data_class' => Observation::class,
                    ])
                 ->setRequired('sections')
    ;
    }
}
