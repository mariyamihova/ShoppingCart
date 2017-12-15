<?php

namespace ShoppingCartBundle\Form;

use ShoppingCartBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddEditProductForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name")
            ->add("category",EntityType::class,
            array('class'=>Category::class))
            ->add("description")
            ->add("quantity")
            ->add("priority")
            ->add("imageUrl")
            ->add("price", MoneyType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'shopping_cart_bundle_add_edit_product_form';
    }
}
