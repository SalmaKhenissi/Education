<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class , [
                'label' => 'Nom'
            ])
            ->add('lastName', TextType::class , [
                'label' => 'Prénom'
            ])
            ->add('email', EmailType::class ,[
                'label' => 'E-mail'
            ])
            ->add('phone', TelType::class ,[
                'label' => 'Téléphone'
            ])
            ->add('subject', TextType::class , [
                'label' => 'Objet'
            ])
            ->add('message', TextareaType::class , [
                'label' => 'Message'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
