<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Category;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\StringType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                ],
                'required' => true,
            ])
            ->add('quantity', IntegerType::class,
            [
                'label' => 'Quantity',
                'required' => true,
                'attr' => [
                    'min' => 0,
                ]
            ])
            ->add('price', FloatType::class,
            [
                'label' => 'Price',
                'required' => true,
                'attr' => [
                    'min' => 0,
                ]
            ])
            ->add('image', StringType::class,
            [
                'label' => 'Product Image',
                'required' => true,
            ])
            ->add('category', EntityType::class,
            [
                'label' => 'Category',
                'requỉed' => true,
                'class' => Category::class,
                'choice_label' => 'name',
                'mutiple' => 'true',
                'expanded' => 'true,'
            ])
            ->add('description', TextType::class,
            [
                'label' => 'Description',
                'required' => true,
            ])
            ->add('manufacturer', EntityType::class,
            [
                'required' => true,
                'class' => Author::class,
                'choice_label' => 'name',
                'multiple' => 'false',
                'expanded' => 'false',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
