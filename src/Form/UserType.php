<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,array('label'=>"Email",'attr'=>array('require'=>'require', 'class'=>'form-control form-group')))
            ->add('prenom',TextType::class, array('label'=>'prenom','attr'=>array('require'=>'require', 'class'=>'form-control form-group')))
            ->add('nom',TextType::class, array('label'=>'nom','attr'=>array('require'=>'require', 'class'=>'form-control form-group')))
            ->add('roles')
            ->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'invalid_message' => 'Champs non identiques.',
                'options' => ['attr' => ['class' => 'form-control form-group']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de Passe'],
                'second_options' => ['label' => 'Répéter le Mot de Passe']
                ])
            ->add('Valider', SubmitType::class, array('attr'=>array('class'=>'btn btn-success form-group')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
