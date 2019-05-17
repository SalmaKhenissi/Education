<?php

namespace App\Form;

use App\Entity\Section;
use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $sections = $options['sections'];

        $builder
            ->add('type' , ChoiceType::class , [
                'choices' => $this->getTypeChoices() ,
                'label' => 'Type'
        ])
            ->add('description',TextareaType::class,[
                'label' => ' Description '
            ])
            ->add('docFile', VichFileType::class ,[
                'required' => false ,
                'allow_delete' => false,
                'download_uri'=> false,
                'download_label'=> false,
                'label' => ' Fichier '
            
            ])
            ->add('sections',EntityType::class , [
                    'class' => Section::class,
                    'choices' => $sections ,
                    'multiple' => true  ,
                    'label' => 'Classes'
             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
            ])
            ->setRequired('sections')
        ;
    }
    private function getTypeChoices()
    {
        $choices = Document::TYPE ;
        $output = [];
        foreach ($choices as $k => $v)
        {
            $output[$v] = $k ;
        }
        return $output ;
    }
}
