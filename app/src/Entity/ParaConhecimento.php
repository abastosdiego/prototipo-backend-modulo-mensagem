<?php

namespace App\Entity;

use App\Repository\ParaConhecimentoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ParaConhecimentoRepository::class)]
class ParaConhecimento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'paraConhecimentos')]
    private ?Mensagem $mensagem = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['show_mensagem'])]
    private ?Usuario $usuario = null;

    #[ORM\Column]
    #[Groups(['show_mensagem'])]
    private ?bool $ciente = null;

    public function __construct(Mensagem $mensagem, Usuario $usuario)
    {
        $this->mensagem = $mensagem;
        $this->usuario = $usuario;
        $this->ciente = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMensagem(): ?Mensagem
    {
        return $this->mensagem;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function ciente(): ?bool
    {
        return $this->ciente;
    }

    public function marcarCiente(): void
    {
        $this->ciente = true;
    }
}
