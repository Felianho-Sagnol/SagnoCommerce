<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class InscriptionType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',TextType::class,$this->getConfiguration('Prenom','Votre Prenom ...'))
            ->add('name',TextType::class,$this->getConfiguration('Nom','Votre nom  ...'))
            ->add('email',EmailType::class,$this->getConfiguration('Email','Votre Addresse email ...'))
            ->add('phone',TextType::class,$this->getConfiguration('Téléphone','Votre Numero de téléphone valide...'))
            ->add('password',PasswordType::class,$this->getConfiguration('Mot de passe','Votre mot de passe ...'))
            //->add('picture')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
