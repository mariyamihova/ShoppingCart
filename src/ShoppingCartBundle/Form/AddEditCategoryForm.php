<?php

namespace ShoppingCartBundle\Form;

use Doctrine\ORM\EntityRepository;
use ShoppingCartBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddEditCategoryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add("name",TextType::class)
            ->add("imageUrl")
            ->add("parent",EntityType::class,
                array('class'=>Category::class,
                    'empty_data'=>null,
                    'multiple'=>false,
                    'placeholder'=>'-----',
                    'required'=>false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('cat')
                            ->where('cat.parent IS NULL')
                            ->orderBy('cat.parent', 'DESC');
                    }));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'shopping_cart_bundle_add_edit_product_form';
    }
}
