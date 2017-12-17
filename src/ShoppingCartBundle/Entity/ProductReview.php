<?php

namespace ShoppingCartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductReview
 *
 * @ORM\Table(name="product_review")
 * @ORM\Entity(repositoryClass="ShoppingCartBundle\Repository\ProductReviewRepository")
 */
class ProductReview
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
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     * @var int
     *
     * @ORM\Column(name="rate", type="integer")
     */
    private $rate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity="ShoppingCartBundle\Entity\User", inversedBy="reviews")
     */
    private $user;

    /**
     * @var Product product
     * @ORM\ManyToOne(targetEntity="ShoppingCartBundle\Entity\Product", inversedBy="reviews")
     */
    private $product;

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
     * Set comment
     *
     * @param string $comment
     *
     * @return ProductReview
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set rate
     *
     * @param integer $rate
     *
     * @return ProductReview
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return ProductReview
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return ProductReview;
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return ProductReview;
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }

}

