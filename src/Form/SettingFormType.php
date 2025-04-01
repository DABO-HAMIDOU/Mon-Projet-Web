<?php

namespace App\Form;

use App\Entity\Setting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Country;

class SettingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('webSiteName', TextType::class)
            ->add('websiteUrl', UrlType::class)
            ->add('description', TextType::class)
            ->add('email', EmailType::class)
            ->add('phone', TelType::class)
            ->add('street', Texttype::class)
            ->add('city', Texttype::class)
            ->add('postalCode', Texttype::class)
            ->add('state', CountryType::class,[
                "placeholder"=>"veillez selectionner un pays"
            ])
            ->add('facebookLink', UrlType::class)
            ->add('instagramLink', UrlType::class)
            ->add('tiktokLink', UrlType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Setting::class,
        ]);
    }
}
