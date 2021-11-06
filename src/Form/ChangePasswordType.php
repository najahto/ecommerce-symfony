<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'disabled' => true,
                'attr' => array(
                    'placeholder' => 'First name'
                )
            ])
            ->add('lastname', TextType::class, [
                'disabled' => true,
                'attr' => array(
                    'placeholder' => 'Last name'
                )
            ])
            ->add('email', EmailType::class, [
                'disabled' => true,
                'attr' => array(
                    'placeholder' => 'Email'
                )
            ])
            ->add('old_password', PasswordType::class, [
                'label' => 'Old password',
                'mapped' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Enter old password'
                )
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => true,
                'first_options' => ['label' => 'New password', 'attr' => array(
                    'placeholder' => 'New password'
                )],
                'second_options' => ['label' => 'Confirm new password', 'attr' => array(
                    'placeholder' => 'Confirm new Password'
                )],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter new password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 255,
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'update'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
