<?php

namespace App\Form;

use App\Data\SearchData;
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

class SearchBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true
            ])
            ->add('author',TextType::class, [
                'attr'=>['class' =>'my-input-class', 'placeholder' => 'Ex : Anthony'],
                "required"=>false,
                "label"=>"Auteur : ",

            ])
            ->add('reference',TextType::class, [
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
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}