<?php

namespace ShoppingCartBundle\Form;


use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Entity\Promotion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionCategoryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add("promotion", EntityType::class, [
                "class" => Promotion::class,
                "placeholder" => "Choose promotion",

            ])
            ->add("category", EntityType::class, [
                "class" => Category::class,
                "label" => "Products category",
                "placeholder" => "Choose category",

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'shopping_cart_bundle_promotion_category_form';
    }
}
