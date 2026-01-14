<?php

namespace App\Entity;

use App\Repository\ChampionsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChampionsRepository::class)]
class Champions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $gender = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $role = [];

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $species = [];

    #[ORM\Column(length: 50)]
    private ?string $resource = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $range_type = [];

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $region = [];

    #[ORM\Column]
    private ?int $release_year = null;

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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getRole(): array
    {
        return $this->role;
    }

    public function setRole(array $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getSpecies(): array
    {
        return $this->species;
    }

    public function setSpecies(array $species): static
    {
        $this->species = $species;

        return $this;
    }

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function setResource(string $resource): static
    {
        $this->resource = $resource;

        return $this;
    }

    public function getRangeType(): array
    {
        return $this->range_type;
    }

    public function setRangeType(array $range_type): static
    {
        $this->range_type = $range_type;

        return $this;
    }

    public function getRegion(): array
    {
        return $this->region;
    }

    public function setRegion(array $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getReleaseYear(): ?int
    {
        return $this->release_year;
    }

    public function setReleaseYear(int $release_year): static
    {
        $this->release_year = $release_year;

        return $this;
    }
}
