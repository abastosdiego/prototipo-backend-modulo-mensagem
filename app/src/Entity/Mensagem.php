<?php

namespace App\Entity;

use App\Repository\MensagemRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MensagemRepository::class)]
#[Groups(['show_mensagem'])]
class Mensagem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 17)]
    private ?string $data_hora = null;

    #[ORM\Column(length: 100)]
    private ?string $assunto = null;

    #[ORM\Column(length: 1000)]
    private ?string $texto = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $observacao = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $data_entrada = null;

    #[ORM\Column(length: 20)]
    private ?string $sigilo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $prazo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $data_autorizacao = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Unidade $unidade_origem = null;

    #[ORM\ManyToMany(targetEntity: Unidade::class, inversedBy: 'mensagens_destino')]
    #[ORM\JoinTable(name: "mensagem_unidades_destino")]
    private Collection $unidades_destino;

    #[ORM\ManyToMany(targetEntity: Unidade::class, inversedBy: 'mensagens_informacao')]
    #[ORM\JoinTable(name: "mensagem_unidades_informacao")]
    private Collection $unidades_informacao;

    #[ORM\OneToMany(mappedBy: 'mensagem', targetEntity: Tramite::class, cascade: ['persist'])]
    private Collection $tramites;

    #[ORM\OneToMany(mappedBy: 'mensagem', targetEntity: Comentario::class, cascade: ['persist', 'remove'], orphanRemoval:true)]
    private Collection $comentarios;

    public function __construct(array $valores, Unidade $unidadeOrigem, array $unidadesDestino, array $unidadesInformacao)
    {
        $this->unidade_origem = $unidadeOrigem;
        $this->unidades_destino = new ArrayCollection();
        $this->unidades_informacao = new ArrayCollection();
        $this->data_entrada = new \DateTime('now');

        $this->carregarValores($valores, $unidadesDestino, $unidadesInformacao);
        $this->tramites = new ArrayCollection();
        $this->comentarios = new ArrayCollection();
    }

    public function carregarValores(array $valores, array $unidadesDestino, array $unidadesInformacao) {
        if(isset($valores['data_hora'])) {
            $this->data_hora = $valores['data_hora'];
        } else {
            $this->data_hora = 'R000000Z/JAN/2024';
        }

        if(isset($valores['assunto'])) {
            $this->assunto = $valores['assunto'];
        }

        if(isset($valores['texto'])) {
            $this->texto = $valores['texto'];
        }

        if(isset($valores['observacao'])) {
            $this->observacao = $valores['observacao'];
        }

        if(isset($valores['sigilo'])) {
            $this->sigilo = $valores['sigilo'];
        }

        if(isset($valores['prazo'])) {
            $this->prazo = new \DateTime($valores['prazo']);
        } else {
            $this->prazo = null;
        }
        
        $this->unidades_destino = new ArrayCollection();
        foreach($unidadesDestino as $unidade) {
            $this->addUnidadesDestino($unidade);
        }

        $this->unidades_informacao = new ArrayCollection();
        foreach($unidadesInformacao as $unidade) {
            $this->addUnidadesInformacao($unidade);
        }

    }

    public function getId() {
        return $this->id;
    }

    public function getDataHora() {
        return $this->data_hora;
    }

    public function getAssunto() {
        return $this->assunto;
    }

    public function getTexto() {
        return $this->texto;
    }

    public function getObservacao() {
        return $this->observacao;
    }

    public function getDataEntrada() {
        return $this->data_entrada;
    }

    public function getSigilo() {
        return $this->sigilo;
    }

    public function getPrazo() {
        return $this->prazo;
    }

    public function getDataAutorizacao() {
        return $this->data_autorizacao;
    }

    public function getUnidadeOrigem(): ?Unidade
    {
        return $this->unidade_origem;
    }

    public function setAutorizado() {
        $this->data_autorizacao = new DateTime('now');
    }

    /**
     * @return Collection<int, Unidade>
     */
    public function getUnidadesDestino(): Collection
    {
        return $this->unidades_destino;
    }

    public function addUnidadesDestino(Unidade $unidadesDestino): static
    {
        if (!$this->unidades_destino->contains($unidadesDestino)) {
            $this->unidades_destino->add($unidadesDestino);
        }

        return $this;
    }

    public function removeUnidadesDestino(Unidade $unidadesDestino): static
    {
        $this->unidades_destino->removeElement($unidadesDestino);

        return $this;
    }

    /**
     * @return Collection<int, Unidade>
     */
    public function getUnidadesInformacao(): Collection
    {
        return $this->unidades_informacao;
    }

    public function addUnidadesInformacao(Unidade $unidadesInformacao): static
    {
        if (!$this->unidades_informacao->contains($unidadesInformacao)) {
            $this->unidades_informacao->add($unidadesInformacao);
        }

        return $this;
    }

    public function removeUnidadesInformacao(Unidade $unidadesInformacao): static
    {
        $this->unidades_informacao->removeElement($unidadesInformacao);

        return $this;
    }

    public function criarTramite(Unidade $unidade, Usuario $tramiteAtual, array $usuariosTramiteFuturo): static
    {
        if ($this->getTramite($unidade)) { 
            throw new \DomainException("Trâmite já existe!");
        }

        $tramite = new Tramite($this, $unidade, $tramiteAtual);
        $this->tramites->add($tramite);

        $tramite->criarTramiteFuturo($usuariosTramiteFuturo);

        return $this;
    }

    public function getTramite(Unidade $unidade) : Tramite | null {
        foreach($this->tramites as $tramite) {
            if ($tramite->getUnidade()->getId() == $unidade->getId()) {
                return (object) $tramite;
            }
        }
        return null;
    }

    /**
     * @return Collection<int, Tramite>
     */
    public function getTramites(): Collection
    {
        return $this->tramites;
    }

    /**
     * @return Collection<int, Comentario>
     */
    public function getComentarios(): Collection
    {
        return $this->comentarios;
    }

    public function getComentario(int $idComentario): Comentario
    {
        foreach($this->comentarios as $comentario) {
            if ($comentario->getId() === $idComentario) { return (object) $comentario; }
        }
        throw new \DomainException("Comentário não existe!");
    }

    public function addComentario(string $texto, Usuario $usuario): Comentario
    {
        $comentario = new Comentario($texto, $this, $usuario->getUnidade(), $usuario);

        if (!$this->comentarios->contains($comentario)) {
            $this->comentarios->add($comentario);
        }

        return $comentario;
    }

    public function changeComentario(int $idComentario, string $texto): void
    {
        $comentario = $this->getComentario($idComentario);
        $comentario->alterarTexto($texto);
    }

    public function removeComentario(int $idComentario): void
    {
        $comentario = $this->getComentario($idComentario);
        $this->comentarios->removeElement($comentario);
    }

}
