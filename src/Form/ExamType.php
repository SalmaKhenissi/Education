<?php

namespace App\Form;

use App\Entity\Exam;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class ExamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    { 
        $section = $options['section'];
        $courseRepository = $options['courseRepository'];
        $quarterRepository = $options['quarterRepository'];

        $builder
            ->add('quarter', EntityType::class ,[
                'class' => 'App\Entity\Quarter' ,
                'choices' => $quarterRepository->findBySchoolYear($section->getSchoolYear()),
                'multiple' => false ,
                'label' => ' Trimestre'
                ])
            ->add('type', ChoiceType::class , [
                'choices' => $this->getTypeChoices() ,
                'label' => 'Type'
            ])
            ->add('passAt',BirthdayType::class, [
                'label' => ' Date ',
                 'widget' => 'single_text'
            ])
            ->add('startAt', TimeType::class, [
                'label' => 'Début',
                'widget' => 'single_text' 
            ])
            ->add('finishAt', TimeType::class, [
                'label' => 'Fin',
                'widget' => 'single_text' 
            ])
            ->add('course', EntityType::class , [
                'class' => 'App\Entity\Course' ,
                'choices' => $courseRepository->findByLevel($section->getLevel()),
                'multiple'=>false ,
                'label' => ' Cours' 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Exam::class,
            ])
            ->setRequired('section')
            ->setRequired('courseRepository')
            ->setRequired('quarterRepository')
    ;
    }

    private function getTypeChoices()
    {
        $choices = Exam::TYPE ;
        $output = [];
        foreach ($choices as $k => $v)
        {
            $output[$v] = $k ;
        }
        return $output ;
    }
}
