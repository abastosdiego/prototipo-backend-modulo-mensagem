<?php

namespace App\Entity;

use App\Repository\MensagemRepository;
use DateTime;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DomainException;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MensagemRepository::class)]
#[Groups(['show_mensagem'])]
class Mensagem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 17, nullable: true)]
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
    private ?\DateTimeInterface $prazo_transmissao = null;

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

    #[ORM\OneToMany(mappedBy: 'mensagem', targetEntity: Tramite::class, cascade: ['persist', 'remove'], orphanRemoval:true)]
    private Collection $tramites;

    #[ORM\OneToMany(mappedBy: 'mensagem', targetEntity: Comentario::class, cascade: ['persist', 'remove'], orphanRemoval:true)]
    private Collection $comentarios;

    #[ORM\Column]
    private ?bool $rascunho = null;

    #[ORM\Column]
    private ?bool $exige_resposta = false;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $prazo_resposta = null;

    public function __construct(array $inputData, Unidade $unidadeOrigem, array $unidadesDestino, array $unidadesInformacao = array())
    {
        $this->unidade_origem = $unidadeOrigem;
        $this->unidades_destino = new ArrayCollection();
        $this->unidades_informacao = new ArrayCollection();
        $this->data_entrada = new \DateTime('now');
        $this->tramites = new ArrayCollection();
        $this->comentarios = new ArrayCollection();
        $this->rascunho = true; //RN001
        $this->exige_resposta = false;

        $this->criarDataHoraMinuta(); //RN002

        $this->carregarValores($inputData, $unidadesDestino, $unidadesInformacao);
    }

    public function carregarValores(array $inputData, array $unidadesDestino, array $unidadesInformacao): void {

        if(isset($inputData['assunto'])) {
            $this->assunto = $inputData['assunto'];
        }

        if(isset($inputData['texto'])) {
            $this->texto = $inputData['texto'];
        }

        if(isset($inputData['observacao'])) {
            $this->observacao = $inputData['observacao'];
        }

        if(isset($inputData['sigilo'])) {
            $this->sigilo = $inputData['sigilo'];
        }

        ///RN007 (Falta implementar parte da regra de negócio)
        if(isset($inputData['prazo_transmissao'])) {
            try{
                $this->prazo_transmissao = new \DateTime($inputData['prazo_transmissao']);
            } catch (\Exception $ex) {
                throw new \DomainException('Prazo de transmissão inválido');
            }
        } else {
            $this->prazo_transmissao = null;
        }
        
        $this->unidades_destino = new ArrayCollection();
        foreach($unidadesDestino as $unidade) {
            if ($unidade === null) {throw new DomainException('Unidade destino inválida!');}
            $this->addUnidadesDestino($unidade);
        }

        $this->unidades_informacao = new ArrayCollection();
        foreach($unidadesInformacao as $unidade) {
            $this->addUnidadesInformacao($unidade);
        }

        if(isset($inputData['exige_resposta'])) {
            $this->exige_resposta = $inputData['exige_resposta'];
        }

        if(isset($inputData['prazo_resposta'])) {
            try{
                $this->prazo_resposta = new \DateTime($inputData['prazo_resposta']);
            } catch (\Exception $ex) {
                throw new \DomainException('Prazo de resposta inválido');
            }
        } else {
            $this->prazo_resposta = null;
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

    public function getPrazoTransmissao(): ?\DateTimeInterface {
        return $this->prazo_transmissao;
    }

    public function getDataAutorizacao() {
        return $this->data_autorizacao;
    }

    public function isAutorizado() {
        if ($this->data_autorizacao) {
            return true;
        } else {
            return false;
        }
    }

    public function getUnidadeOrigem(): ?Unidade
    {
        return $this->unidade_origem;
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

    public function criarTramite(Unidade $unidade, Usuario $usuarioAtual, array $usuariosTramiteFuturo): void
    {
        //RN006
        if ($this->getTramite($unidade)) { 
            throw new \DomainException("Trâmite já existe!");
        }

        $tramite = new Tramite($this, $unidade, $usuarioAtual);
        $this->tramites->add($tramite);

        $tramite->criarTramiteFuturo($usuariosTramiteFuturo);
    }

    /**
     * @return Collection<int, Tramite>
     */
    public function getTramites(): Collection
    {
        return $this->tramites;
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

    public function removeComentario(int $idComentario): void
    {
        $comentario = $this->getComentario($idComentario);
        $this->comentarios->removeElement($comentario);
    }

    public function autorizar(): void {
        //RN004
        if($this->data_autorizacao !== null) {throw new \DomainException('Mensagem já foi autorizada!');}

        //RN005
        if(count($this->unidades_destino) === 0) {throw new \DomainException('Mensagem sem OM destino não pode ser autorizada!');}

        $dataHoje = new DateTime('now');
        $this->data_autorizacao = $dataHoje;
        $this->criarDataHora();
    }

    /**
     * RN003
     */
    private function criarDataHora(): void {

        $dataHoje = new DateTime('now', new DateTimeZone('UTC'));

        $dia = $dataHoje->format('d');
        $meses = array('JAN','FEV','MAR','ABR','MAI','JUN','JUL','AGO','SET','OUT','NOV','DEZ');
        $mes = $meses[$dataHoje->format('n') - 1];
        $ano = $dataHoje->format('Y');
        $hora = $dataHoje->format('G');
        $minuto = $dataHoje->format('i');

        $this->data_hora = 'R' . $dia . $hora . $minuto . 'Z/'. $mes . '/' . $ano;
    }

    /**
     * RN002
     */
    private function criarDataHoraMinuta(): void {
        
        $dataHoje = new DateTime('now', new DateTimeZone('UTC'));

        $dia = $dataHoje->format('d');
        $meses = array('JAN','FEV','MAR','ABR','MAI','JUN','JUL','AGO','SET','OUT','NOV','DEZ');
        $mes = $meses[$dataHoje->format('n') - 1];
        $ano = $dataHoje->format('Y');
        $hora = $dataHoje->format('G');
        $minuto = $dataHoje->format('i');

        $this->data_hora = 'M' . $dia . $hora . $minuto . 'Z/'. $mes . '/' . $ano;
    }

    public function rascunho(): ?bool
    {
        return $this->rascunho;
    }

    public function removerDoRascunho() {
        $this->rascunho = false;
    }

    public function exigeResposta(): ?bool
    {
        return $this->exige_resposta;
    }

    public function getPrazoResposta(): ?\DateTimeInterface
    {
        return $this->prazo_resposta;
    }

}
