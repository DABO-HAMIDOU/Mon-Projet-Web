<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex; // ✅ Correction ici
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'svp entrez le mot de passe',
                        ]),
                        new Length([
                            'min' => 12,
                            'minMessage' => 'Le mot de passe doit contenir au minimum {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 255,
                            'maxMessage' => 'Le mot de passe ne doit pas dépasser {{ limit }} caractères',
                        ]),
                        new Regex([
                        'pattern' => "/^[0-9a-zA-Z-_'@# áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]+$/i",
                            'match' => true,
                            'message' => "Le mot de passe doit contenir uniquement des lettres, des chiffres, le tiret du milieu ou l'underscore."
                        ]),
                        new NotCompromisedPassword(),
                    ],
                ],
                'invalid_message' => 'Le mot de passe doit être identique à sa confirmation.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
