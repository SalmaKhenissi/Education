<?php

namespace App\Form;

use App\Entity\Room;
use App\Entity\Seance;

use App\Entity\Section;
use App\Entity\Teacher;
use App\Repository\CourseRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SeanceType extends AbstractType
{

    private $repo;

    public function __construct(CourseRepository $repo){
        $this->repo = $repo; 
    }

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
                'choice_label' => 'number',
                'multiple' => false ,
                'label' => ' Salle'
          ])
            ->add('teacher' , EntityType::class , [
                'class' => 'App\Entity\Teacher' ,
                'multiple' => false ,
                 'label' => 'Enseignant',
            ])
            /*->add('course' , EntityType::class , [
                        'class' => 'App\Entity\Course' ,
                        'choice_label' => 'name',
                        'multiple' => false ,
                         'label' => 'Cours',
            ])*/
            
            
        ;
        /*$builder->get('course')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event){
                $form = $event->getForm();
                dump($form->getData());
                $form->getParent()->add('teacher' , EntityType::class , [
                    'class' => 'App\Entity\Teacher' ,
                    'choices' => $form->getData()->getTeachers(),
                ]); 
            }

        );

       
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function(FormEvent $event){
                $form = $event->getForm();
                $data = $event ->getData();
                $teacher = $data->getTeacher();
                


                if($teacher){
                   
                    $form->get('course')->setData($teacher->getCourse());
                    $form->add('teacher', EntityType::class , [
                        'class' => 'App\Entity\Teacher' ,
                        'choices' => $teacher->getCourse()->getTeachers(),
                    ]);
                }
                else{
                    
                    $form->add('teacher', EntityType::class , [
                        'class' => 'App\Entity\Teacher' ,
                        'placeholder' => 'sélectionner un enseignant' , 
                        'choices' => [],
                    ]);
                }
               
           
                
            }

        );*/
       
        
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
