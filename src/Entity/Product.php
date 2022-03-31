<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("prods:read")
     * @Groups("orders:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("prods:read")
     * @Groups("orders:read")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups("prods:read")
     * @Groups("orders:read")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     * @Groups("prods:read")
     * @Groups("orders:read")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @Groups("prods:read")
     * @Groups("orders:read")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=OrderLine::class, mappedBy="product")
     */
    private $orderLines;

    /**
     * @ORM\OneToMany(targetEntity=OrderLine::class, mappedBy="product", orphanRemoval=true)
     */
    private $orderLine;

    public function __construct()
    {
        $this->orderLines = new ArrayCollection();
        $this->orderLine = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, OrderLine>
     */
    public function getOrderLines(): Collection
    {
        return $this->orderLines;
    }

    public function addOrderLine(OrderLine $orderLine): self
    {
        if (!$this->orderLines->contains($orderLine)) {
            $this->orderLines[] = $orderLine;
            $orderLine->setProduct($this);
        }

        return $this;
    }

    public function removeOrderLine(OrderLine $orderLine): self
    {
        if ($this->orderLines->removeElement($orderLine)) {
            // set the owning side to null (unless already changed)
            if ($orderLine->getProduct() === $this) {
                $orderLine->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrderLine>
     */
    public function getOrderLine(): Collection
    {
        return $this->orderLine;
    }
}
