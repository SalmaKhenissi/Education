<?php

namespace App\Form;

use App\Entity\Section;
use App\Entity\Teacher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType; 
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TeacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('email', EmailType::class ,[
                'label' => 'E-mail'
            ])
            ->add('tel', TelType::class ,[
                'label' => 'Téléphone'
            ])
            ->add('type' , ChoiceType::class , [
                    'choices' => $this->getTypeChoices() ,
                    'label' => 'Type'
            ])
            ->add('cin'  , TextType::class ,[
                'label' => 'Cin'
            ])
            ->add('address' , TextType::class , [
                'label' => 'Adresse'
            ])
            ->add('specialty' , TextType::class , [
                'label' => 'Spécialité'
            ])
            /*->add('save', SubmitType::class,  [
                'label' => 'Modifier'
            ])*/
        ;
    }

    public function getParent()
    {
        return UserType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Teacher::class,
        ]);
    }

    private function getTypeChoices()
    {
        $choices = Teacher::TYPE ;
        $output = [];
        foreach ($choices as $k => $v)
        {
            $output[$v] = $k ;
        }
        return $output ;
    }
}
