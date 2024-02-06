<?php

namespace App\Entity;

use App\Repository\TramiteFuturoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TramiteFuturoRepository::class)]
class TramiteFuturo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $ordem = null;

    #[ORM\ManyToOne(inversedBy: 'tramite_futuro')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tramite $tramite = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdem(): ?int
    {
        return $this->ordem;
    }

    public function setOrdem(int $ordem): static
    {
        $this->ordem = $ordem;

        return $this;
    }

    public function getTramite(): ?Tramite
    {
        return $this->tramite;
    }

    public function setTramite(?Tramite $tramite): static
    {
        $this->tramite = $tramite;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }
}
