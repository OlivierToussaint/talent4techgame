<?php

/*
 * This file is part of the Talent4tech project.
 * Olivier Toussaint <olivier@toussaint.fr>
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'mail'
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => User::ROLE_USER,
                    'Administrateur' => User::ROLE_ADMIN,
                ],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('name')
            ->add('hp', IntegerType::class)
            ->add('ap', IntegerType::class)
            ->add('avatar', FileType::class, [
                'label' => 'avatar',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // everytime you edit the Product details
                'required' => false,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
