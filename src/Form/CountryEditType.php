<?php

namespace App\Form;

use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountryEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('code', TextType::class)
        ->add('name', TextType::class)
        ->add('continent', TextType::class)
        ->add('region', TextType::class)
        ->add('localName', TextType::class)
        ->add('capital', IntegerType::class)
        ->add('code2', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
