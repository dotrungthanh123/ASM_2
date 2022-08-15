<?php

namespace App\Entity;

use App\Repository\CompletedRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompletedRepository::class)]
class Completed
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(inversedBy: 'completed', targetEntity: order::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $orderId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): ?order
    {
        return $this->orderId;
    }

    public function setOrderId(order $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }
}
