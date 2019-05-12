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

class Exam2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    { 
        $exam = $options['exam'];
        $teacherRepository = $options['teacherRepository'];
        $roomRepository = $options['roomRepository'];
        $examRepository = $options['examRepository'];
        $rooms=$roomRepository->findAll();
        $teachers=$teacherRepository->findAll();
        $examsPerDate=$examRepository->findByPassAt($exam->getPassAT());

        $builder
            ->add('room', EntityType::class ,[
                'class' => 'App\Entity\Room' ,
                'choices' => $roomRepository->findByAvailability($exam , $rooms ,$examsPerDate),
                'multiple' => false ,
                'label' => ' Salle'
          ])
          ->add('teachers', EntityType::class ,[
            'class' => 'App\Entity\Teacher' ,
            'choices' => $teacherRepository->findByAvailability($exam , $teachers ,$examsPerDate),
            'multiple' => true ,
            'label' => ' Superviseur'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Exam::class,
            ])
            ->setRequired('exam')
            ->setRequired('teacherRepository')
            ->setRequired('roomRepository')
            ->setRequired('examRepository')
    ;
    }

}
