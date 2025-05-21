<?php

namespace App\Entity;

use App\Repository\UserEnergySnapshotRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserEnergySnapshotRepository::class)]
class UserEnergySnapshot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userEnergySnapshots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $timestamp = null;

    #[ORM\Column]
    private ?float $consumptionKwh = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $consumptionDelta = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

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

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getConsumptionKwh(): ?float
    {
        return $this->consumptionKwh;
    }

    public function setConsumptionKwh(float $consumptionKwh): static
    {
        $this->consumptionKwh = $consumptionKwh;

        return $this;
    }

    public function getConsumptionDelta(): ?float
    {
        return $this->consumptionDelta;
    }

    public function setConsumptionDelta(?float $value): static
    {
        $this->consumptionDelta = $value;
        return $this;
    }
}
