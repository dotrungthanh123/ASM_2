<?php

namespace App\Form;

use App\Entity\Manufacturer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ManufacturerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class,
        [
            'label' => 'Manufacturer name',
            'attr' => [
                'minlength' => 4,
                'maxlength' => 50
            ],
            'required' => true
        ])
        ->add('address',TextType::class,
        [
            'label' => 'Manufacturer address',
            'required' => true,
            'attr' => [
                'minlength' => 3,
                'maxlength' => 50
            ]
        ])
            ->add('contact',textType::class,
            [
                'label' => 'Manufacturer contact',
                'required' => true,
                'attr' => [
                    'min' => 15,
                    'max' => 80
                ]
            ])
            ->add('image' ,TextType::class,
            [
                'label' => 'Manufacturer image',
                'attr' => [
                    'maxlength' => 255
                ],
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Manufacturer::class,
        ]);
    }
}
