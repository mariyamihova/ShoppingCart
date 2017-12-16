<?php

namespace ShoppingCartBundle\Form;

use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\Promotion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add("promotion", EntityType::class, [
                "class" => Promotion::class,
                "placeholder" => "Choose promotion",

            ])
            ->add("product", EntityType::class, [
                "class" => Product::class,
                "label" => "Products",
                "placeholder" => "Choose product",

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'shopping_cart_bundle_promotion_product_type';
    }
}
