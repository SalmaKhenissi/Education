<?php

namespace App\Form;

use App\Entity\Quarter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class QuarterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startAt',BirthdayType::class, [
                'label' => ' Début',
                 'widget' => 'single_text'
            ])
            ->add('finishAt',BirthdayType::class, [
                'label' => ' Fin',
                 'widget' => 'single_text'
            ])
            ->add('number', ChoiceType::class , [
                'choices' => $this->getNumberChoices() ,
                'label' => 'Numéro'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quarter::class,
        ]);
    }

    private function getNumberChoices()
    {
        $choices = Quarter::NUMBER ;
        $output = [];
        foreach ($choices as $k => $v)
        {
            $output[$v] = $k ;
        }
        return $output ;
    }
}
