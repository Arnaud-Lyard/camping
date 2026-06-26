<?php

namespace App\Domain\Equipment\Entity;

use App\Domain\Equipment\Enum\EquipmentStatus;
use App\Domain\Equipment\Repository\EquipmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipmentRepository::class)]
class Equipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 510)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $ordre = null;

    #[ORM\Column(enumType: EquipmentStatus::class)]
    private ?EquipmentStatus $status = null;

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

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): static
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getStatus(): ?EquipmentStatus
    {
        return $this->status;
    }

    public function setStatus(EquipmentStatus $status): static
    {
        $this->status = $status;

        return $this;
    }
}
