<?php

namespace App\Entity;

use App\Repository\TramitePassadoRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TramitePassadoRepository::class)]
class TramitePassado
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $data_hora = null;

    #[ORM\ManyToOne(inversedBy: 'tramites_passado')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tramite $tramite = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    public function __construct(Tramite $tramite, Usuario $usuario)
    {
        $this->data_hora = new DateTimeImmutable();
        $this->tramite = $tramite;
        $this->usuario = $usuario;        
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }
}
