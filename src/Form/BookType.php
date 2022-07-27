<?php

namespace App\Form;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder ->add('category',EntityType::class,[
            'class'=>Category::class,
            'multiple'    => true,
            'mapped'=>false,
            'expanded' => TRUE
        ])

            ->add('author',TextType::class, [
                'attr'=>['class' =>'my-input-class', 'placeholder' => 'Ex : Anthony'],
                "required"=>false,
                "label"=>"Auteur : ",

            ])
            ->add('reference',TelType::class, [
                'attr'=>['class' =>'my-input-class', 'placeholder' => 'Ex : Du côté de chez swann'],
                "required"=>false,
                "label"=>"Titre : "
            ])
            ->add('rechercher', SubmitType::class,['attr'=>['class' =>'btn btn-primary py-3 px-5 my-input-class']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'data_class' => Category::class,
        ]);
    }
}