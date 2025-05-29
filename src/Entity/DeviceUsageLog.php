<?php

namespace App\Entity;

use App\Repository\DeviceUsageLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: DeviceUsageLogRepository::class)]
class DeviceUsageLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $title = null;

    #[ORM\ManyToOne(targetEntity: Device::class, inversedBy: 'deviceUsageLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Device $device = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(nullable: true)]
    private ?float $energyUsedKWh = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function setDevice(?Device $device): static
    {
        $this->device = $device;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(?\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }

    public function setEndedAt(?\DateTimeImmutable $endedAt): static
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDurationPrettified(): ?string
    {
        if ($this->startedAt && $this->endedAt) {
            $interval = $this->startedAt->diff($this->endedAt);

            $parts = [];
            if ($interval->h > 0) {
                $parts[] = $interval->h . 'h';
            }
            if ($interval->i > 0) {
                $parts[] = $interval->i . 'm';
            }
            if ($interval->s > 0 || empty($parts)) {
                $parts[] = $interval->s . 's';
            }

            return implode(' ', $parts);
        }
        return null;
    }

    public function getEnergyUsedKWh(): ?float
    {
        return $this->energyUsedKWh;
    }

    public function setEnergyUsedKWh(?float $energyUsedKWh): static
    {
        $this->energyUsedKWh = $energyUsedKWh;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function calculateEnergyUsage(): void
    {
        if ($this->endedAt && $this->startedAt && $this->device) {
            $this->duration = $this->endedAt->getTimestamp() - $this->startedAt->getTimestamp();

            if ($this->duration > 0 && $this->device->getPowerWatt()) {
                $watts = $this->device->getPowerWatt();
                $this->energyUsedKWh = ($watts * $this->duration) / 3600000;
            }
        }
    }

    public function setTitleFromData(): void
    {
        if ($this->getDevice() && $this->startedAt && $this->endedAt) {
            $this->title = sprintf(
                '%s - %s to %s (%s)',
                $this->device->getName(),
                $this->startedAt->format('H:i'),
                $this->endedAt->format('H:i'),
                $this->getDurationPrettified()
            );
        }
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function __toString(): string
    {
        if ($this->title) {
            return $this->title;
        }

        return sprintf('Usage on %s', $this->startedAt?->format('Y-m-d H:i') ?? 'unknown time');
    }
}
