<?php

namespace App\Entity;

use App\Repository\QuotesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuotesRepository::class)]
class Quotes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name_champion = null;

    #[ORM\Column(length: 255)]
    private ?string $quote = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameChampion(): ?string
    {
        return $this->name_champion;
    }

    public function setNameChampion(string $name): static
    {
        $this->name_champion = $name_champion;

        return $this;
    }

    public function getQuote(): ?string
    {
        return $this->quote;
    }

    public function setQuote(string $quote): static
    {
        $this->quote = $quote;

        return $this;
    }
}
