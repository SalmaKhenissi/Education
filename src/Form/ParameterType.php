<?php

namespace App\Form;

use App\Entity\Parameter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ParameterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('schoolYear', TextType::class ,[
                'label' => ' Année scolaire '
            ])
            ->add('quarter', ChoiceType::class , [
                'choices' => $this->getQuarterChoices() ,
                'label' => 'Trimestre'
            ])
            ->add('phone', TextType::class ,[
                'label' => ' Téléphone '
            ])
            ->add('address', TextType::class ,[
                'label' => ' Adresse '
            ])
            ->add('email', TextType::class ,[
                'label' => ' E-mail '
            ])
            
            /*->add('programFile', VichFileType::class ,[
                'required' => false ,
                'allow_delete' => true,
                'label' => '  '
            
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Parameter::class,
        ]);
    }

    private function getQuarterChoices()
    {
        $choices = Parameter::NUMBER ;
        $output = [];
        foreach ($choices as $k => $v)
        {
            $output[$v] = $k ;
        }
        return $output ;
    }
}
