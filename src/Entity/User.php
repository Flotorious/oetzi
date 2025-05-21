<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $street = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $postalCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $meterIdentifier = null;

    #[ORM\OneToMany(
        targetEntity: Device::class,
        mappedBy: 'user',
        cascade: ['remove'],
        orphanRemoval: true
    )]
    private Collection $devices;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $feedUrl = null;

    #[ORM\OneToMany(targetEntity: UserEnergySnapshot::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $userEnergySnapshots;

    public function __construct()
    {
        $this->devices = new ArrayCollection();
        $this->userEnergySnapshots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // Clear temporary sensitive data
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): static
    {
        $this->street = $street;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;
        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): static
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;
        return $this;
    }

    public function getMeterIdentifier(): ?string
    {
        return $this->meterIdentifier;
    }

    public function setMeterIdentifier(?string $meterIdentifier): static
    {
        $this->meterIdentifier = $meterIdentifier;
        return $this;
    }

    /**
     * @return Collection<int, Device>
     */
    public function getDevices(): Collection
    {
        return $this->devices;
    }

    public function addDevice(Device $device): static
    {
        if (!$this->devices->contains($device)) {
            $this->devices->add($device);
            $device->setUser($this);
        }
        return $this;
    }

    public function removeDevice(Device $device): static
    {
        if ($this->devices->removeElement($device)) {
            if ($device->getUser() === $this) {
                $device->setUser(null);
            }
        }
        return $this;
    }

    public function getFeedUrl(): ?string
    {
        return $this->feedUrl;
    }

    public function setFeedUrl(?string $feedUrl): static
    {
        $this->feedUrl = $feedUrl;
        return $this;
    }

    /**
     * @return Collection<int, UserEnergySnapshot>
     */
    public function getUserEnergySnapshots(): Collection
    {
        return $this->userEnergySnapshots;
    }

    public function addUserEnergySnapshot(UserEnergySnapshot $userEnergySnapshot): static
    {
        if (!$this->userEnergySnapshots->contains($userEnergySnapshot)) {
            $this->userEnergySnapshots->add($userEnergySnapshot);
            $userEnergySnapshot->setUser($this);
        }
        return $this;
    }

    public function removeUserEnergySnapshot(UserEnergySnapshot $userEnergySnapshot): static
    {
        if ($this->userEnergySnapshots->removeElement($userEnergySnapshot)) {
            if ($userEnergySnapshot->getUser() === $this) {
                $userEnergySnapshot->setUser(null);
            }
        }
        return $this;
    }
}
