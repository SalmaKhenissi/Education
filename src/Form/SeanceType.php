<?php

namespace App\Form;
use App\Entity\Course;
use App\Entity\Seance;
use App\Entity\Section;
use App\Repository\TeacherRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SeanceType extends AbstractType
{
    public function __construct()
    {
        
    }
    public function buildForm(FormBuilderInterface $builder, array $options  )
    {

        $section = $options['section'];
        $courseRepository = $options['courseRepository'];
        
        

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
            
            ->add('course' , EntityType::class , [
                'class' => 'App\Entity\Course' ,
                'choices' => $courseRepository->findByLevel($section->getLevel()),
                'multiple'=>false ,
                'label' => ' Cours' 
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
                 ])
                 ->setRequired('section')
                 ->setRequired('courseRepository');
    }
}
