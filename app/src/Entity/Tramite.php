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

    #[ORM\OneToMany(mappedBy: 'tramite', targetEntity: TramiteFuturo::class, orphanRemoval: true)]
    private Collection $tramite_futuro;

    public function __construct()
    {
        $this->tramite_futuro = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMensagem(): ?Mensagem
    {
        return $this->mensagem;
    }

    public function setMensagem(?Mensagem $mensagem): static
    {
        $this->mensagem = $mensagem;

        return $this;
    }

    public function getUnidade(): ?Unidade
    {
        return $this->unidade;
    }

    public function setUnidade(?Unidade $unidade): static
    {
        $this->unidade = $unidade;

        return $this;
    }

    /**
     * @return Collection<int, TramiteFuturo>
     */
    public function getTramiteFuturo(): Collection
    {
        return $this->tramite_futuro;
    }

    public function addTramiteFuturo(TramiteFuturo $tramiteFuturo): static
    {
        if (!$this->tramite_futuro->contains($tramiteFuturo)) {
            $this->tramite_futuro->add($tramiteFuturo);
            $tramiteFuturo->setTramite($this);
        }

        return $this;
    }

    public function removeTramiteFuturo(TramiteFuturo $tramiteFuturo): static
    {
        if ($this->tramite_futuro->removeElement($tramiteFuturo)) {
            // set the owning side to null (unless already changed)
            if ($tramiteFuturo->getTramite() === $this) {
                $tramiteFuturo->setTramite(null);
            }
        }

        return $this;
    }
}
