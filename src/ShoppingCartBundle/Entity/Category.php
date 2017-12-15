<?php

namespace ShoppingCartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Category
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="ShoppingCartBundle\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var ArrayCollection|Product[]
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="category", mappedBy="parent")
     */
    private $children;
    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToOne(targetEntity="category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     *     )
     */
    private $parent;

    /**
     * @var string
     *
     * @ORM\Column(name="image_url", type="text")
     */

    private $imageUrl;

    /**
     * @ORM\ManyToMany(targetEntity="ShoppingCartBundle\Entity\Promotion", inversedBy="categories")
     * @ORM\JoinTable(name="category_promotions")
     *
     * @var ArrayCollection
     */

    private $promotions;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->children=new ArrayCollection();
        $this->parent=new ArrayCollection();

    }
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return ArrayCollection|Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return ArrayCollection|Category[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     * @return Category
     */
    public function setImageUrl(string $imageUrl)
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }


    /**
     * @return ArrayCollection|Category[]
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Category $parent
     */
    public function setParent(Category $parent=null)
    {
        $this->parent = $parent;

    }

    /**
     * @return ArrayCollection|null
     */
    public function getPromotions()
    {
        return $this->promotions;
    }

    /**
     * @param ArrayCollection $promotions
     * @return Category
     */
    public function setPromotions(ArrayCollection $promotions)
    {
        $this->promotions = $promotions;
        return $this;
    }


    public function __toString()
    {
        return $this->getName();
    }

}

