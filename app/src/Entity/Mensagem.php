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

    #[ORM\ManyToOne(inversedBy: 'mensagensOrigem')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Unidade $unidadeOrigem = null;

    #[ORM\ManyToMany(targetEntity: Unidade::class, inversedBy: 'mensagens_destino')]
    #[ORM\JoinTable(name: "mensagem_unidades_destino")]
    private Collection $unidades_destino;

    #[ORM\ManyToMany(targetEntity: Unidade::class, inversedBy: 'mensagens_informacao')]
    #[ORM\JoinTable(name: "mensagem_unidades_informacao")]
    private Collection $unidades_informacao;

    #[ORM\OneToMany(mappedBy: 'mensagem', targetEntity: Tramite::class)]
    private Collection $tramites;

    #[ORM\OneToMany(mappedBy: 'mensagem', targetEntity: Comentario::class, cascade: ['persist', 'remove'])]
    private Collection $comentarios;

    public function __construct(array $valores, Unidade $unidadeOrigem, array $unidadesDestino, array $unidadesInformacao)
    {
        $this->unidades_destino = new ArrayCollection();
        $this->unidades_informacao = new ArrayCollection();
        $this->setDataEntrada(new \DateTime('now'));

        $this->alterarValores($valores, $unidadeOrigem, $unidadesDestino, $unidadesInformacao);
        $this->tramites = new ArrayCollection();
        $this->comentarios = new ArrayCollection();
    }

    public function alterarValores(array $valores, Unidade $unidadeOrigem, array $unidadesDestino, array $unidadesInformacao) {
        if(isset($valores['data_hora'])) {
            $this->setDataHora($valores['data_hora']);
        } else {
            $this->setDataHora('R000000Z/JAN/2024');
        }

        if(isset($valores['assunto'])) {
            $this->setAssunto($valores['assunto']);
        }

        if(isset($valores['texto'])) {
            $this->setTexto($valores['texto']);
        }

        if(isset($valores['observacao'])) {
            $this->setObservacao($valores['observacao']);
        }

        if(isset($valores['sigilo'])) {
            $this->setSigilo($valores['sigilo']);
        }

        if(isset($valores['prazo'])) {
            $this->setPrazo(new \DateTime($valores['prazo']));
        } else {
            $this->setPrazo(null);
        }

        $this->setUnidadeOrigem($unidadeOrigem);
        
        $this->unidades_destino = new ArrayCollection();
        foreach($unidadesDestino as $unidade) {
            $this->addUnidadesDestino($unidade);
        }

        $this->unidades_informacao = new ArrayCollection();
        foreach($unidadesInformacao as $unidade) {
            $this->addUnidadesInformacao($unidade);
        }

    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDataHora(): ?string
    {
        return $this->data_hora;
    }

    private function setDataHora(string $data_hora): static
    {
        $this->data_hora = $data_hora;

        return $this;
    }

    public function getAssunto(): ?string
    {
        return $this->assunto;
    }

    public function setAssunto(string $assunto): static
    {
        $this->assunto = $assunto;

        return $this;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(string $texto): static
    {
        $this->texto = $texto;

        return $this;
    }

    public function getObservacao(): ?string
    {
        return $this->observacao;
    }

    public function setObservacao(?string $observacao): static
    {
        $this->observacao = $observacao;

        return $this;
    }

    public function getDataEntrada(): ?\DateTimeInterface
    {
        return $this->data_entrada;
    }

    private function setDataEntrada(\DateTimeInterface $data_entrada): static
    {
        $this->data_entrada = $data_entrada;

        return $this;
    }

    public function getSigilo(): ?string
    {
        return $this->sigilo;
    }

    public function setSigilo(string $sigilo): static
    {
        $this->sigilo = $sigilo;

        return $this;
    }

    public function getPrazo(): ?\DateTimeInterface
    {
        return $this->prazo;
    }

    public function setPrazo(?\DateTimeInterface $prazo): static
    {
        $this->prazo = $prazo;

        return $this;
    }

    public function getUnidadeOrigem(): ?Unidade
    {
        return $this->unidadeOrigem;
    }

    public function setUnidadeOrigem(?Unidade $unidadeOrigem): static
    {
        $this->unidadeOrigem = $unidadeOrigem;

        return $this;
    }

    public function getDataAutorizacao() {
        return $this->data_autorizacao;
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

    /**
     * @return Collection<int, Tramite>
     */
    public function getTramites(): Collection
    {
        return $this->tramites;
    }

    public function addTramite(Tramite $tramite): static
    {
        if (!$this->tramites->contains($tramite)) {
            $this->tramites->add($tramite);
            $tramite->setMensagem($this);
        }

        return $this;
    }

    public function removeTramite(Tramite $tramite): static
    {
        if ($this->tramites->removeElement($tramite)) {
            // set the owning side to null (unless already changed)
            if ($tramite->getMensagem() === $this) {
                $tramite->setMensagem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comentario>
     */
    public function getComentarios(): Collection
    {
        return $this->comentarios;
    }

    public function addComentario(Comentario $comentario): static
    {
        if (!$this->comentarios->contains($comentario)) {
            $this->comentarios->add($comentario);
            $comentario->setMensagem($this);
        }

        return $this;
    }

    public function removeComentario(Comentario $comentario): static
    {
        if ($this->comentarios->removeElement($comentario)) {
            // set the owning side to null (unless already changed)
            if ($comentario->getMensagem() === $this) {
                $comentario->setMensagem(null);
            }
        }

        return $this;
    }
}
