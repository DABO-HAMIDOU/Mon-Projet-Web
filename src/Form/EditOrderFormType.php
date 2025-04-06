<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class EditOrderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $carriers = $options['carriers'];

        $builder
            ->add('deliveryFirstName', TextType::class)
            ->add('deliveryLastName', TextType::class)
            ->add('deliveryEmail', EmailType::class)
            ->add('deliveryPhone', TelType::class)
            ->add('deliveryStreet', TextType::class)
            ->add('deliveryPostalCode', TextType::class)
            ->add('deliveryCity', TextType::class)
            ->add('deliveryCountry', CountryType::class, [
                "placeholder" => "Selectionnez le pays"
            ])
            ->add('carrierName', ChoiceType::class, [
                // looks for choices from this entity

                'choices' => $carriers,


                // uses the User.username property as the visible option string
                'choice_label' => 'name',

                'placeholder' => "Selectionner le livreur",

                // used to render a select box, check boxes or radios
                'multiple' => false,
                'expanded' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'carriers' => []
        
        ]);
    }
}
