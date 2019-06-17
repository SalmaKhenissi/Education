<?php

namespace App\Form;

use App\Entity\Parameter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ParameterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('schoolYear', TextType::class ,[
                'label' => ' Année scolaire ' ,
                'attr' => array(
                    'placeholder' => 'YYYY/YYYY',
                )
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
            ->add('slider1File', VichImageType::class , [
                'required' => false ,
                'label' => 'Slide1' ,
                'allow_delete' => false  ,
                'download_uri'=> false,
                'download_label'=> false,
            ])
            ->add('slider3File', VichImageType::class , [
                'required' => false ,
                'label' => 'Slide3' ,
                'allow_delete' => false  ,
                'download_uri'=> false,
                'download_label'=> false,
            ])
            ->add('slider2File', VichImageType::class , [
                'required' => false ,
                'label' => 'Slide2' ,
                'allow_delete' => false  ,
                'download_uri'=> false,
                'download_label'=> false,
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
