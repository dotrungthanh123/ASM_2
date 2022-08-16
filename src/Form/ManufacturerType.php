<?php

namespace App\Form;

use App\Entity\Manufacturer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ManufacturerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class,
        [
            'label' => 'Name',
            'attr' => [
                'minlength' => 4,
                'maxlength' => 50
            ],
            'required' => true,
            // 'constraints' =>
            // [
            //     new NotBlank([
            //         'message' => 'Enter Name Of Manufacturer',
            //     ]),
            // ],
        ])
        ->add('address', TextType::class,
        [
            'label' => 'Address',
            'required' => true,
            'attr' => [
                'minlength' => 3,
                'maxlength' => 50
            ]
        ])
            ->add('contact', TextType::class,
            [
                'label' => 'Contact',
                'required' => true,
                'attr' => [
                    'min' => 15,
                    'max' => 80
                ]
            ])
            ->add('image' , TextType::class,
            [
                'label' => 'Image',
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
