<?php

namespace App\Entity;

use App\Repository\ComentarioRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ComentarioRepository::class)]
class Comentario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['show_mensagem'])]
    private ?int $id = null;

    #[ORM\Column(length: 1000)]
    #[Groups(['show_mensagem'])]
    private ?string $texto = null;

    #[ORM\Column]
    #[Groups(['show_mensagem'])]
    private ?\DateTimeImmutable $data_hora = null;

    #[ORM\ManyToOne(inversedBy: 'comentarios')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mensagem $mensagem = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['show_mensagem'])]
    private ?Unidade $unidade = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['show_mensagem'])]
    private ?Usuario $usuario = null;

    public function __construct(string $texto, Mensagem $mensagem, Unidade $unidade, Usuario $usuario)
    {
        $this->texto = $texto;
        $this->data_hora = new DateTimeImmutable("now");
        $this->mensagem = $mensagem;
        $this->unidade  = $unidade;
        $this->usuario = $usuario;
    }

    public function alterarTexto(string $texto) {
        $this->texto = $texto;
    }

    public function getId(): int
    {
        return (int) $this->id;
    }

    // public function getTexto(): ?string
    // {
    //     return $this->texto;
    // }

    // public function setTexto(string $texto): static
    // {
    //     $this->texto = $texto;

    //     return $this;
    // }

    // public function getDataHora(): ?\DateTimeImmutable
    // {
    //     return $this->data_hora;
    // }

    // public function setDataHora(\DateTimeImmutable $data_hora): static
    // {
    //     $this->data_hora = $data_hora;

    //     return $this;
    // }

    // public function getMensagem(): ?Mensagem
    // {
    //     return $this->mensagem;
    // }

    // public function setMensagem(?Mensagem $mensagem): static
    // {
    //     $this->mensagem = $mensagem;

    //     return $this;
    // }

    // public function getUnidade(): ?Unidade
    // {
    //     return $this->unidade;
    // }

    // public function setUnidade(?Unidade $unidade): static
    // {
    //     $this->unidade = $unidade;

    //     return $this;
    // }

    // public function getUsuario(): ?Usuario
    // {
    //     return $this->usuario;
    // }

    // public function setUsuario(?Usuario $usuario): static
    // {
    //     $this->usuario = $usuario;

    //     return $this;
    // }
}
