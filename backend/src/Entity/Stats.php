<?php

namespace App\Entity;

use App\Repository\StatsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatsRepository::class)]
class Stats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $gamemode = null;

    #[ORM\Column]
    private ?\DateTime $date = null;

    #[ORM\Column]
    private ?int $nb_try = null;

    #[ORM\Column]
    private ?bool $successful = null;

    #[ORM\Column]
    private ?int $id_user = null;

    #[ORM\Column]
    private ?int $id_champion = null;

    #[ORM\Column(type: Types::JSONB, nullable: true)]
    private mixed $attempts = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGamemode(): ?string
    {
        return $this->gamemode;
    }

    public function setGamemode(string $gamemode): static
    {
        $this->gamemode = $gamemode;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getNbTry(): ?int
    {
        return $this->nb_try;
    }

    public function setNbTry(int $nb_try): static
    {
        $this->nb_try = $nb_try;

        return $this;
    }

    public function getSuccessful(): ?bool
    {
        return $this->successful;
    }

    public function setSuccessful(?bool $successful): static
    {
        $this->successful = $successful;

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdChampion(): ?int
    {
        return $this->id_champion;
    }

    public function setIdChampion(int $id_champion): static
    {
        $this->id_champion = $id_champion;

        return $this;
    }

    public function getAttempts(): mixed
    {
        return $this->attempts;
    }

    public function setAttempts(mixed $attempts): static
    {
        $this->attempts = $attempts;

        return $this;
    }
}
