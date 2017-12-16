<?php

namespace ShoppingCartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="ShoppingCartBundle\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;
    /**
     * @var float
     * @ORM\Column(name="promo_price", type="decimal", precision=10, scale=2)
     */
    private $promotionalPrice;
    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;
    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;
    /**
     * @ORM\Column(name="image_url", type="text")
     *
     * @var string
     */
    private $imageUrl;
    /**
     * @ORM\ManyToMany(targetEntity="ShoppingCartBundle\Entity\User", mappedBy="products")
     *
     * @var ArrayCollection
     */
    private $users;
    /**
     * @ORM\ManyToOne(targetEntity="ShoppingCartBundle\Entity\User", inversedBy="ownedProducts")
     */
    private $seller;

    /**
     * @var int
     *
     * @ORM\Column(name="priority", type="integer")
     */
    private $priority;

    /**
     * @ORM\ManyToMany(targetEntity="ShoppingCartBundle\Entity\Promotion", inversedBy="products")
     * @ORM\JoinTable(name="product_promotions")
     *
     * @var ArrayCollection
     */
    private $promotions;

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
     * @return Product
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
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Product
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     *
     * @return Product
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return string|null
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageName
     * @return Product
     */
    public function setImageUrl(string $imageUrl)
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    /**
     * @return ArrayCollection|User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param ArrayCollection $users
     * @return Product
     */
    public function setUsers(ArrayCollection $users)
    {
        $this->users = $users;
        return $this;
    }

    /**
     * @return User $user
     */
    public function getSeller()
    {

        return $this->seller;
    }

    /**
     * @param int $seller|null
     * @return Product
     */
    public function setSeller($seller=null)
    {
        $this->seller = $seller;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return int|null
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority(int $priority)
    {
        $this->priority = $priority;
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
     * @return Product
     */
    public function setPromotions(ArrayCollection $promotions)
    {
        $this->promotions = $promotions;
        return $this;
    }

    public function setPromotion(Promotion $promotion)
    {
        $this->promotions[] = $promotion;
        $this->setPromotionalPrice($this->getPrice()-(($promotion->getDiscount()/100)*$this->getPrice()));
    }
    public function unsetPromotion(Promotion $promotion)
    {
        $this->promotions->removeElement($promotion);
        $this->promotionalPrice = 0.00;
    }
    public function __toString()
    {
       return $this->name;
    }

    /**
     * @return float
     */
    public function getPromotionalPrice()
    {
        return $this->promotionalPrice;
    }

    /**
     * @param float $promotionalPrice
     */
    public function setPromotionalPrice(float $promotionalPrice)
    {
        $this->promotionalPrice = $promotionalPrice;
    }

}

