<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchProductsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                "class" => Category::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                'placeholder' => "Nos catégories"
            ])
            ->add('keywords', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => "Recherchez un produit"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "method" => "GET",
            "csrf_protection" => false
        ]);
    }
}
