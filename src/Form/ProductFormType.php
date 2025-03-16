<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('code', TextType::class)
            ->add('slug', TextType::class)
            ->add('shortDescription', TextareaType::class)
            ->add('longDescription', TextareaType::class)
            ->add('brand', TextType::class)
            ->add('sellingPrice', NumberType::class)
            ->add('quantity', NumberType::class)
            ->add('isNewArrival', CheckboxType::class)
            ->add('isBetterSeller', CheckboxType::class)
            ->add('isAvaible',  ChoiceType::class, [
            'choices'  => [
                'oui' => true,
                'Non' => false,    
                ],
                'expanded' => false,
                'multiple' => false,
                'placeholder' => "Disponibilité produit "
                ]) 
            ->add('Categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
            // ->add('imageFile', VichImageType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
