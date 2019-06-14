<?php

namespace App\Form;
use App\Entity\Level;
use App\Entity\Course;
use App\Entity\Specialty;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle',TextType::class ,[
                'label' => ' Libellé '
            ])
            ->add('coefficient',TextType::class ,[
                'label' => ' coefficient '
            ])
            ->add('nbrExams',IntegerType::class ,[
                'label' => ' Nombre d\'examens '
            ])
            ->add('level' , EntityType::class , [
                'class' => 'App\Entity\Level' ,
                'multiple' => false ,
                 'label' => 'Niveau',
            ])
            ->add('specialty' , EntityType::class , [
                'class' => 'App\Entity\Specialty' ,
                'multiple' => false ,
                 'label' => 'Spécialité',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
