<?php

namespace App\Entity;

use App\Repository\TramiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TramiteRepository::class)]
class Tramite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tramites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mensagem $mensagem = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Unidade $unidade = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario_atual = null;

    #[ORM\OneToMany(mappedBy: 'tramite', targetEntity: TramiteFuturo::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $tramites_futuro;

    #[ORM\OneToMany(mappedBy: 'tramite', targetEntity: TramitePassado::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $tramites_passado;

    public function __construct(Mensagem $mensagem, Unidade $unidade, Usuario $tramiteAtual)
    {
        $this->mensagem = $mensagem;
        $this->unidade = $unidade;
        $this->usuario_atual = $tramiteAtual;
        $this->tramites_futuro = new ArrayCollection();
        $this->tramites_passado = new ArrayCollection();
    }

    public function getUnidade(): ?Unidade
    {
        return $this->unidade;
    }

    /**
     * @return Collection<int, TramiteFuturo>
     */
    public function getTramitesFuturos(): Collection
    {
        return $this->tramites_futuro;
    }

    /**
     * @return TramiteFuturo
     */
    public function getProximoTramiteFuturo(): TramiteFuturo
    {
        if (count($this->getTramitesFuturos()) == 0) { new \DomainException("Trâmite não definido!");}

        return (object) $this->tramites_futuro[0];
    }

    public function criarTramiteFuturo(array $usuarios): void {
        $this->tramites_futuro = new ArrayCollection();

        foreach($usuarios as $chave => $usuario){
            $tramiteFuturo = new TramiteFuturo($this, $chave+1, $usuario);
            $this->tramites_futuro->add($tramiteFuturo);
        }
    }

    public function getUsuarioAtual(): ?Usuario
    {
        return $this->usuario_atual;
    }

    public function encaminharProximo(): void {

        if(count($this->tramites_futuro) == 0) {throw new \DomainException("Não existe próximo no trâmite");}

        //Usuário tramite atual passa a ser do trâmite passado.
        $tramitePassado = new TramitePassado($this, $this->getUsuarioAtual());
        $this->tramites_passado->add($tramitePassado);

        //Próximo usuário do trâmite passa a ser o atual.
        $this->usuario_atual = $this->getProximoTramiteFuturo()->getUsuario();

        //Remover proximo item do trâmite futuro, pois virou o atual.
        $this->tramites_futuro->removeElement($this->getProximoTramiteFuturo());
    }

    public function encaminharPara(Usuario $usuario): void {
        
        //Usuário tramite atual passa a ser do trâmite passado.
        $tramitePassado = new TramitePassado($this, $this->getUsuarioAtual());
        $this->tramites_passado->add($tramitePassado);

        //Próximo usuário do trâmite passa a ser o enviado por parâmetro.
        $this->usuario_atual = $usuario;
    }

}
