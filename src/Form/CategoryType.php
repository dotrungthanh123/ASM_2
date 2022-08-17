<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,
<<<<<<< HEAD
                [
                    'label' => 'Author name',
                    'attr' => [
                        'minlength' => 3,
                        'maxlength' => 30
                    ]
                ]
            );
=======
            [
                'label' => 'Category',
                'attr' => [
                    'minlength' => 3,
                    'maxlength' => 30
                ]
            ])
            ->add('image', TextType::class,
            [
                'label'=>'Link of image',
            ])
            ->add('description', TextType::class,
            [
                'label'=>'Description',
                'attr' => [
                      'maxlength' => 300
                ]
            ])
        ;
>>>>>>> e679dffcaddf1a5b856c5214c47026e06b4ddfe4
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
