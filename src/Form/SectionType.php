<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Section;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('level'  , ChoiceType::class ,[
                'label' => ' Niveau' ,
                'choices' => $this->getLevelChoices()
            ])
            ->add('nbrGroup'  , TextType::class ,[
                'label' => ' Groupe '
            ])
            ->add('schoolYear'  , TextType::class ,[
                'label' => ' Année Scolaire '
            ])
            ->add('track'  , ChoiceType::class ,[
                'label' => ' Filiére' ,
                'choices' => $this->getTrackChoices()
            ])                                                                                                                                          
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
        ]);
    }

    private function getLevelChoices()
    {
        $choices = Section::LEVEL ;
        $output = [];
        foreach ($choices as $k => $v)
        {
            $output[$v] = $k ;
        }
        return $output ;
    }
    private function getTrackChoices()
    {
        $choices = Section::TRACK ;
        $output = [];
        foreach ($choices as $k => $v)
        {
            $output[$v] = $k ;
        }
        return $output ;
    }
}
