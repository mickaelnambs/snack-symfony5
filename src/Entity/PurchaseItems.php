<?php

namespace App\Entity;

use App\Repository\PurchaseItemsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PurchaseItemsRepository::class)
 */
class PurchaseItems
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $productName;

    /**
     * @ORM\Column(type="integer")
     */
    private $productPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity=Items::class, inversedBy="purchaseItems")
     */
    private $item;

    /**
     * @ORM\ManyToOne(targetEntity=Purchases::class, inversedBy="purchaseItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $purchase;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductPrice(): ?int
    {
        return $this->productPrice;
    }

    public function setProductPrice(int $productPrice): self
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getItem(): ?Items
    {
        return $this->item;
    }

    public function setItem(?Items $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getPurchase(): ?Purchases
    {
        return $this->purchase;
    }

    public function setPurchase(?Purchases $purchase): self
    {
        $this->purchase = $purchase;

        return $this;
    }
}
