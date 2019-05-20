<?php

namespace App\Form;

use App\Entity\Student;
use App\Entity\Punishment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PunishmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $section= $options['section'];

        $builder
            ->add('type', ChoiceType::class , [
                'choices' => $this->getTypeChoices() ,
                'label' => 'Type'
            ])
            ->add('description',TextareaType::class ,[
                'label' => ' Description '
            ])
            ->add('student',EntityType::class , [
                'class' => Student::class,
                'choices' => $section->getStudents() ,
                'multiple' => false  ,
                'label' => 'Student'
         ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Punishment::class,
        ])
        ->setRequired('section');
    }

    private function getTypeChoices()
    {
        $choices = Punishment::TYPE ;
        $output = [];
        foreach ($choices as $k => $v)
        {
            $output[$v] = $k ;
        }
        return $output ;
    }
}
