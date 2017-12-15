<?php

namespace ShoppingCartBundle\Form;

use ShoppingCartBundle\Entity\Role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddEditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("email")
            ->add("fullName")
            ->add("money")
            ->add("roles", EntityType::class, [
                "class" => Role::class,
                "multiple" => true
            ])
            ->add("password", RepeatedType::class, [
                "type" => PasswordType::class
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'shopping_cart_bundle_add_edit_user_type';
    }
}
