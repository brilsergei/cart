<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=LineItem::class, mappedBy="cart", orphanRemoval=true)
     */
    private $line_items;

    public function __construct()
    {
        $this->line_items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|LineItem[]
     */
    public function getLineItems(): Collection
    {
        return $this->line_items;
    }

    public function addLineItem(LineItem $lineItem): self
    {
        if (!$this->line_items->contains($lineItem)) {
            $this->line_items[] = $lineItem;
            $lineItem->setCart($this);
        }

        return $this;
    }

    public function removeLineItem(LineItem $lineItem): self
    {
        if ($this->line_items->contains($lineItem)) {
            $this->line_items->removeElement($lineItem);
            // set the owning side to null (unless already changed)
            if ($lineItem->getCart() === $this) {
                $lineItem->setCart(null);
            }
        }

        return $this;
    }

    public function jsonSerialize() {
        return [
            'id' => $this->getId(),
            'line_items' => $this->getLineItems()->toArray(),
        ];
    }

}
