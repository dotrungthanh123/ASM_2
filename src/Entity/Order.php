<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $time;

    #[ORM\ManyToOne(targetEntity: user::class, inversedBy: 'orders')]
    private $user;

    #[ORM\OneToMany(mappedBy: 'ord3r', targetEntity: OrderDetail::class)]
    private $orderId;

    #[ORM\OneToOne(mappedBy: 'orderId', targetEntity: Completed::class, cascade: ['persist', 'remove'])]
    private $completed;

    public function __construct()
    {
        $this->orderId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, OrderDetail>
     */
    public function getOrderId(): Collection
    {
        return $this->orderId;
    }

    public function addOrderId(OrderDetail $orderId): self
    {
        if (!$this->orderId->contains($orderId)) {
            $this->orderId[] = $orderId;
            $orderId->setOrd3r($this);
        }

        return $this;
    }

    public function removeOrderId(OrderDetail $orderId): self
    {
        if ($this->orderId->removeElement($orderId)) {
            // set the owning side to null (unless already changed)
            if ($orderId->getOrd3r() === $this) {
                $orderId->setOrd3r(null);
            }
        }

        return $this;
    }

    public function getCompleted(): ?Completed
    {
        return $this->completed;
    }

    public function setCompleted(Completed $completed): self
    {
        // set the owning side of the relation if necessary
        if ($completed->getOrderId() !== $this) {
            $completed->setOrderId($this);
        }

        $this->completed = $completed;

        return $this;
    }
}
