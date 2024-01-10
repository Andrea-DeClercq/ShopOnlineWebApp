<?php

namespace App\Form;

use App\Entity\Cart;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idUser', IntegerType::class)
            ->add('idProduct', IntegerType::class)
            ->add('quantity', IntegerType::class)
            ->add('payed', CheckboxType::class, [
                'required' => false,
            ])
            ->add('confirmed', CheckboxType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
