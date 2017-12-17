<?php

namespace ShoppingCartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="ShoppingCartBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    CONST INITIAL_CASH = 3000;

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
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank(message = "Fullname cannot be blank")
     * @ORM\Column(name="fullName", type="string", length=255)
     */
    private $fullName;

    /**
     * @var string
     * @Assert\NotBlank(message = "Password cannot be blank")
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="money", type="decimal", precision=14, scale=2)
     */
    private $money;

    /**
     * @var Role[]|@var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ShoppingCartBundle\Entity\Role", inversedBy="users")
     * @ORM\JoinTable(name="users_roles")
     */
    private $roles;
    /**
     * Products added in the cart
     * @var Product[]|@var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ShoppingCartBundle\Entity\Product", inversedBy="users")
     * @ORM\JoinTable(name="cart")
     */
    private $products;

    /**
     * @var ProductOrder[]|ArrayCollection $orders
     * @ORM\OneToMany(targetEntity="ShoppingCartBundle\Entity\ProductOrder", mappedBy="user")
     * @ORM\OrderBy({"date":"desc"})
     *
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity="ShoppingCartBundle\Entity\Product", mappedBy="seller")
     */
    private $ownedProducts;

    /**
     * @ORM\ManyToMany(targetEntity="ShoppingCartBundle\Entity\Promotion", inversedBy="users")
     * @ORM\JoinTable(name="user_promotions")
     *
     * @var ArrayCollection
     */

    private $promotions;

    /**
     * @ORM\OneToMany(targetEntity="ShoppingCartBundle\Entity\ProductReview", mappedBy="user")
     * @var ProductReview[]|ArrayCollection $reviews
     */
    private $reviews;

    public function __construct()
    {
        $this->money = self::INITIAL_CASH;
        $this->roles = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->reviews=new ArrayCollection();
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
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set money
     *
     * @param string $money
     *
     * @return User
     */
    public function setMoney($money)
    {
        $this->money = $money;

        return $this;
    }

    /**
     * Get money
     *
     * @return float
     */
    public function getMoney()
    {
        return $this->money;
    }


    public function getRoles()
    {
        $stringRoles = [];
        foreach ($this->roles as $role)
        {
            /** @var $role Role */
            $stringRoles[] = $role->getName();
        }
        return $stringRoles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        $this->confirm = null;
    }

    public function addRole(Role $role)
    {
        $this->roles[] = $role;
    }

    /**
     *  @return ArrayCollection|Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param ArrayCollection|Product[] $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    /**
     * @return  ArrayCollection| ProductOrder[]
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param @var ArrayCollection| @var ProductOrder[] $orders
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
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
     * @return User;
     */
    public function setPromotions(ArrayCollection $promotions)
    {
        $this->promotions = $promotions;
        return $this;
    }

    /**
     * @return ArrayCollection|ProductReview[]
     */
    public function getReviews()
    {
        return $this->reviews;
    }


    /**
     * @return mixed
     */
    public function getOwnedProducts()
    {
        return $this->ownedProducts;
    }

    /**
     * @param mixed $ownedProducts
     */
    public function setOwnedProducts($ownedProducts)
    {
        $this->ownedProducts = $ownedProducts;
    }



    public function __toString()
    {
        return $this->getEmail();
    }

}

