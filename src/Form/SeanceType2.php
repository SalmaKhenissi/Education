<?php

namespace App\Form;
use App\Entity\Seance;
use App\Entity\Teacher;
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

class SeanceType2 extends AbstractType
{
    public function __construct()
    {
        
    }
    public function buildForm(FormBuilderInterface $builder, array $options  )
    {

        $seance = $options['seance'];
        $roomRepository = $options['roomRepository'];
        $teacherRepository = $options['teacherRepository'];
        $seanceRepository = $options['seanceRepository'];

        $rooms=$roomRepository->findAll();
        $course=$seance->getCourse()->getLibelle();
        if($course=="Histoire" ||$course=="Geographie")
        {
            $teachers=$teacherRepository->findBySpecialty("Histoire et Geographie");
        }
        else
        {
            $teachers=$teacherRepository->findBySpecialty($course);
        }
        $seancesPerDay=$seanceRepository->findAllByDay($seance->getDay() ,$seance->getSection());
        
        

        $builder
            
            ->add('room'  , EntityType::class ,[
                'class' => 'App\Entity\Room' ,
                'choices' => $roomRepository->findRoomByAvailability($seance , $rooms ,$seancesPerDay),
                'multiple' => false ,
                'label' => ' Salle'
            ])
            ->add('teacher' , EntityType::class , [
                'class' => 'App\Entity\Teacher' ,
                'choices' => $teacherRepository->findTeacherByAvailability($seance ,$teachers ,$seancesPerDay),
                'multiple' => false ,
                 'label' => 'Enseignant',
            ])
        ;

        
    }

    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                    'data_class' => Seance::class,
                 ])
                 ->setRequired('seance')
                 ->setRequired('roomRepository')
                 ->setRequired('teacherRepository')
                 ->setRequired('seanceRepository');
    }
}
