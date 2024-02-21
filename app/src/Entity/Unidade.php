<?php

namespace App\Entity;

use App\Repository\UnidadeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UnidadeRepository::class)]
class Unidade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Groups(['show_mensagem'])]
    private ?string $sigla = null;

    #[ORM\Column(length: 255)]
    private ?string $nome = null;

    ///#[ORM\OneToMany(mappedBy: 'unidadeOrigem', targetEntity: Mensagem::class)]
    ///private Collection $mensagensOrigem;

    ///#[ORM\ManyToMany(targetEntity: Mensagem::class, mappedBy: 'unidades_destino')]
    ///private Collection $mensagens_destino;

    ///#[ORM\ManyToMany(targetEntity: Mensagem::class, mappedBy: 'unidades_informacao')]
    ///private Collection $mensagens_informacao;

    ///#[ORM\OneToMany(mappedBy: 'unidade', targetEntity: Usuario::class)]
    ///private Collection $usuarios;

    public function __construct()
    {
        ///$this->mensagensOrigem = new ArrayCollection();
        ///$this->mensagens_destino = new ArrayCollection();
        ///$this->mensagens_informacao = new ArrayCollection();
        ///$this->usuarios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSigla(): ?string
    {
        return $this->sigla;
    }

    public function setSigla(string $sigla): static
    {
        $this->sigla = $sigla;

        return $this;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): static
    {
        $this->nome = $nome;

        return $this;
    }

    ////**
    /// * @return Collection<int, Mensagem>
    /// */
    ///public function getMensagensOrigem(): Collection
    ///{
    ///    return $this->mensagensOrigem;
    ///}
///
    ///public function addMensagensOrigem(Mensagem $mensagensOrigem): static
    ///{
    ///    if (!$this->mensagensOrigem->contains($mensagensOrigem)) {
    ///        $this->mensagensOrigem->add($mensagensOrigem);
    ///        $mensagensOrigem->setUnidadeOrigem($this);
    ///    }
///
    ///    return $this;
    ///}
///
    ///public function removeMensagensOrigem(Mensagem $mensagensOrigem): static
    ///{
    ///    if ($this->mensagensOrigem->removeElement($mensagensOrigem)) {
    ///        // set the owning side to null (unless already changed)
    ///        if ($mensagensOrigem->getUnidadeOrigem() === $this) {
    ///            $mensagensOrigem->setUnidadeOrigem(null);
    ///        }
    ///    }
///
    ///    return $this;
    ///}


    ////**
    /// * @return Collection<int, Mensagem>
    /// */
    ///public function getMensagensDestino(): Collection
    ///{
    ///    return $this->mensagens_destino;
    ///}
///
    ///public function addMensagensDestino(Mensagem $mensagensDestino): static
    ///{
    ///    if (!$this->mensagens_destino->contains($mensagensDestino)) {
    ///        $this->mensagens_destino->add($mensagensDestino);
    ///        $mensagensDestino->addUnidadesDestino($this);
    ///    }
///
    ///    return $this;
    ///}
///
    ///public function removeMensagensDestino(Mensagem $mensagensDestino): static
    ///{
    ///    if ($this->mensagens_destino->removeElement($mensagensDestino)) {
    ///        $mensagensDestino->removeUnidadesDestino($this);
    ///    }
///
    ///    return $this;
    ///}

    ////**
    /// * @return Collection<int, Mensagem>
    /// */
    ///public function getMensagensInformacao(): Collection
    ///{
    ///    return $this->mensagens_informacao;
    ///}
///
    ///public function addMensagensInformacao(Mensagem $mensagensInformacao): static
    ///{
    ///    if (!$this->mensagens_informacao->contains($mensagensInformacao)) {
    ///        $this->mensagens_informacao->add($mensagensInformacao);
    ///        $mensagensInformacao->addUnidadesInformacao($this);
    ///    }
///
    ///    return $this;
    ///}
///
    ///public function removeMensagensInformacao(Mensagem $mensagensInformacao): static
    ///{
    ///    if ($this->mensagens_informacao->removeElement($mensagensInformacao)) {
    ///        $mensagensInformacao->removeUnidadesInformacao($this);
    ///    }
///
    ///    return $this;
    ///}

    ////**
    /// * @return Collection<int, Usuario>
    /// */
    ///public function getUsuarios(): Collection
    ///{
    ///    return $this->usuarios;
    ///}
///
    ///public function addUsuario(Usuario $usuario): static
    ///{
    ///    if (!$this->usuarios->contains($usuario)) {
    ///        $this->usuarios->add($usuario);
    ///        $usuario->setUnidade($this);
    ///    }
///
    ///    return $this;
    ///}
///
    ///public function removeUsuario(Usuario $usuario): static
    ///{
    ///    if ($this->usuarios->removeElement($usuario)) {
    ///        // set the owning side to null (unless already changed)
    ///        if ($usuario->getUnidade() === $this) {
    ///            $usuario->setUnidade(null);
    ///        }
    ///    }
///
    ///    return $this;
    ///}
}
