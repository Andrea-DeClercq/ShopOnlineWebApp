<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('userName', TextType::class, [
            'label' => 'Username',
        ])
        ->add('userEmail', EmailType::class, [
            'label' => 'Email',
        ])
        ->add('userPhone', TextType::class, [
            'label' => 'Phone',
        ])
        ->add('userFname', TextType::class, [
            'label' => 'First Name',
        ])
        ->add('userLname', TextType::class, [
            'label' => 'Last Name',
        ])
        ->add('userPassword', PasswordType::class, [
            'label' => 'Password',
        ])
        ->add('userCityId', IntegerType::class, [
            'label' => 'City ID',
        ])
        ->add('userAdress', TextType::class, [
            'label' => 'Address',
        ])
        ->add('userLoginStatus', IntegerType::class, [
            'label' => 'Login Status',
        ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
