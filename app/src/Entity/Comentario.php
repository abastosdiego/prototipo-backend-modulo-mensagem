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

    #[ORM\Column]
    private bool $rascunho = true;

    public function __construct(string $texto, Mensagem $mensagem, Unidade $unidade, Usuario $usuario)
    {
        $this->texto = $texto;
        $this->data_hora = new DateTimeImmutable("now");
        $this->mensagem = $mensagem;
        $this->unidade  = $unidade;
        $this->usuario = $usuario;
        $this->rascunho = true; //RN014
    }

    public function alterarTexto(string $texto) {
        $this->texto = $texto;
    }

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function getTexto() {
        return $this->texto;
    }

    public function getDataHora() {
        return $this->data_hora;
    }

    public function getUnidade() {
        return $this->unidade;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function rascunho(): bool
    {
        return $this->rascunho;
    }

    public function removerRascunho() {
        $this->rascunho = false;
    }

}
