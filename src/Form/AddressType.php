<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Name'
                )
            ])
            ->add('firstname',TextType::class, [
                'required' => true,
                'attr' => array(
                    'placeholder' => 'First name'
                )
            ])
            ->add('lastname',TextType::class, [
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Last name'
                )
            ])
            ->add('company', TextType::class, [
                'attr' => array(
                    'placeholder' => 'Company (optional)'
                )
            ])
            ->add('phone', TelType::class, [
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Phone'
                )
            ])
            ->add('address', TextType::class, [
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Address'
                )
            ])
            ->add('postalCode', TextType::class, [
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Postal code'
                )
            ])
            ->add('city',TextType::class, [
                'required' => true,
                'attr' => array(
                    'placeholder' => 'City'
                )
            ])
            ->add('country',CountryType::class, [
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Country'
                )
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
