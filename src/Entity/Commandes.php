<?php

namespace App\Entity;

use App\Repository\CommandesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandesRepository::class)]
class Commandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $is_send = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsSend(): ?bool
    {
        return $this->is_send;
    }

    public function setIsSend(bool $is_send): static
    {
        $this->is_send = $is_send;

        return $this;
    }
}
