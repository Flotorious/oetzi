<?php

namespace App\Entity;

use App\Repository\DeviceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: DeviceRepository::class)]
class Device
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isActive = false;

    #[ORM\ManyToOne(inversedBy: 'devices')]
    private ?User $user = null;

    #[ORM\Column(nullable: false)]
    private ?float $powerWatt = null;

    /**
     * @var Collection<int, DeviceUsageLog>
     */
    #[ORM\OneToMany(targetEntity: DeviceUsageLog::class, mappedBy: 'device', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $deviceUsageLogs;

    public function __construct()
    {
        $this->deviceUsageLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getLastOpenUsageLog(): ?DeviceUsageLog
    {
        foreach ($this->deviceUsageLogs as $log) {
            if ($log->getEndedAt() === null) {
                return $log;
            }
        }
        return null;
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

    public function getPowerWatt(): ?float
    {
        return $this->powerWatt;
    }

    public function setPowerWatt(?float $powerWatt): static
    {
        $this->powerWatt = $powerWatt;

        return $this;
    }

    /**
     * @return Collection<int, DeviceUsageLog>
     */
    public function getDeviceUsageLogs(): Collection
    {
        return $this->deviceUsageLogs;
    }

    public function addDeviceUsageLog(DeviceUsageLog $deviceUsageLog): static
    {
        if (!$this->deviceUsageLogs->contains($deviceUsageLog)) {
            $this->deviceUsageLogs->add($deviceUsageLog);
            $deviceUsageLog->setDevice($this);
        }

        return $this;
    }

    public function removeDeviceUsageLog(DeviceUsageLog $deviceUsageLog): static
    {
        if ($this->deviceUsageLogs->removeElement($deviceUsageLog)) {
            // set the owning side to null (unless already changed)
            if ($deviceUsageLog->getDevice() === $this) {
                $deviceUsageLog->setDevice(null);
            }
        }

        return $this;
    }

    public function addUsageLog(DeviceUsageLog $log): self
    {
        if (!$this->deviceUsageLogs->contains($log)) {
            $this->deviceUsageLogs[] = $log;
            $log->setDevice($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s (%dW)', $this->name, $this->powerWatt);
    }
}
