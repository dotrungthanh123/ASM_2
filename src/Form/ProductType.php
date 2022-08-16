<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Manufacturer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,
            [
                'label' => 'Name',
                'attr' => [
                    'minlength' => 4,
                    'class' => 'dm'
                ],
                'required' => true
            ])
            ->add('quantity', IntegerType::class,
            [
                'label' => 'Quantity',
                'required' => true,
                'attr' => [
                    'min' => 0,
                ]
            ])
            ->add('price', MoneyType::class,
            [
                'label' => 'Price',
                'required' => true,
                'attr' => [
                    'min' => 0,
                ],
                'currency' => 'USD',
            ])
            ->add('image', TextType::class,
            [
                'label' => 'Product Image',
                'required' => true,
            ])
            ->add('category', EntityType::class,
            [
                'label' => 'Category',
                'required' => true,
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('description', TextType::class,
            [
                'label' => 'Description',
                'required' => true,
            ])
            ->add('manufacturer', EntityType::class, 
            [
                'label' => 'Manufacturer',
                'required' => true,
                'class' => Manufacturer::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

        ]);
    }
}
