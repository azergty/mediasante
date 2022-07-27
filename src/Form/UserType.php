<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('last_name',TextType::class,[
                    "required"=>true,
                    "label"=>"Nom : ",
                    'attr'=>['class' =>'my-input-class', 'placeholder' => 'Ex : Dubois']
            ])


            ->add('first_name',TextType::class, [
                'attr'=>['class' =>'my-input-class', 'placeholder' => 'Ex : Anthony'],
                "required"=>true,
                "label"=>"Prénom : ",

            ])
            ->add('phone',TelType::class, [
                'attr'=>['class' =>'my-input-class', 'placeholder' => 'Ex : 0636253621'],
                    "required"=>true,
                    "label"=>"Téléphone : "
                ])

            ->add('password', PasswordType::class,[
                'attr'=>['class' =>'my-input-class', 'placeholder' => 'Ex : @!o&dsj123'],
                "required"=>true,
                "label"=>"Mot de passe : "
            ])
            ->add('email', EmailType::class,['attr'=>['class' =>'my-input-class', 'placeholder' => 'Ex : anthony.dubois@yahoo.fr'],
                "required"=>true,
                "label"=>"Email : "
            ])
            ->add('save', SubmitType::class,['attr'=>['class' =>'btn btn-primary py-3 px-5 my-input-class']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}