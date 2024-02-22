<?php

namespace App\Entity;

use App\Repository\TramiteFuturoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TramiteFuturoRepository::class)]
class TramiteFuturo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $ordem = 0;

    #[ORM\ManyToOne(inversedBy: 'tramite_futuro')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Tramite $tramite = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['show_mensagem'])]
    private ?Usuario $usuario = null;

    public function __construct(Tramite $tramite, int $ordem, Usuario $usuario) {
        $this->tramite = $tramite;
        $this->ordem = $ordem;
        $this->usuario = $usuario;
    }    

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }
}
