<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ticketKey = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    private ?Offer $offer = null;

    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[ORM\Column(length: 255)]
    private ?string $offerName = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    private ?Purchase $purchase = null;

    public function __construct(User $user)
    {
        // Génération d'une nouvelle clé pour le ticket, concaténée avec celle de l'utilisateur
        $uuid = Uuid::uuid4();
        $this->ticketKey = $user->getUserKey() . substr($uuid->toString(), 0, 10);
        
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTicketKey(): ?string
    {
        return $this->ticketKey;
    }

    public function setTicketKey(string $ticketKey): static
    {
        $this->ticketKey = $ticketKey;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): static
    {
        $this->offer = $offer;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getOfferName(): ?string
    {
        return $this->offerName;
    }

    public function setOfferName(string $offerName): static
    {
        $this->offerName = $offerName;

        return $this;
    }

    public function getPurchase(): ?Purchase
    {
        return $this->purchase;
    }

    public function setPurchase(?Purchase $purchase): static
    {
        $this->purchase = $purchase;

        return $this;
    }
}
