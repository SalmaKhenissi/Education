<?php

namespace App\Form;
use App\Entity\Course;
use App\Entity\Seance;
use App\Entity\Section;
use App\Entity\Teacher;
use App\Repository\CourseRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('day' , ChoiceType::class , [
                'choices' => $this->getDayChoices() ,
                'multiple'=>false ,
                'label' => ' Jour'
             ])
            ->add('startAt' , TimeType::class, [
                'label' => 'Début',
                'widget' => 'single_text' 
            ])
            ->add('finishAt' , TimeType::class, [
                'label' => 'Fin',
                'widget' => 'single_text'
            ])
            ->add('room'  , EntityType::class ,[
                'class' => 'App\Entity\Room' ,
                'multiple' => false ,
                'label' => ' Salle'
          ])
            ->add('teacher' , EntityType::class , [
                'class' => 'App\Entity\Teacher' ,
                'multiple' => false ,
                 'label' => 'Enseignant',
            ])
            ->add('course' , EntityType::class , [
                'class' => 'App\Entity\Course' ,
                'query_builder' => function (CourseRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->where('c.level = :l')
                    ->setParameter('l', $section->getLevel());
                },
                'multiple'=>false ,
                'label' => ' Cours' ,
                'required' => false
            ])
        ;
    }

     

    private function getDayChoices()
    {
        $choices = Seance::DAY ;
        $output = [];
        foreach ($choices as $k => $v)
        {
            $output[$v] = $k ;
        }
        return $output ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }
}
