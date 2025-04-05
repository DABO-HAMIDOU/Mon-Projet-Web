<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        /** @var User */
        $user = $options['user'];

        $builder
            ->add('address', EntityType::class, [
                // looks for choices from this entity
                'class' => Address::class,

                'choices' => $user->getAddresses(),

                'placeholder' => "Selectionner l'adresse de livraison",

                // used to render a select box, check boxes or radios
                'multiple' => false,
                'expanded' => false,

                'constraints' => [
                    new NotBlank([
                        'message' => "L'addresse est obligatoire."
                    ]),
                    new Type(Address::class, "Cette adresse n'est pas valide.")
                ]
            ])
            ->add('carrier', EntityType::class, [
                // looks for choices from this entity
                'class' => Carrier::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'name',

                'placeholder' => "Selectionner le livreur",

                // used to render a select box, check boxes or radios
                'multiple' => false,
                'expanded' => false,

                'constraints' => [
                    new NotBlank([
                        'message' => "Le choix du livreur est obligatoire."
                    ]),
                    new Type(Carrier::class, "Ce livreur est inconnu.")
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "user" => []
        ]);
    }
}
