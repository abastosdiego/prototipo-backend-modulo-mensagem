<?php

namespace App\Controller;

use App\Repository\UnidadeRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/usuario')]
class UsuarioController extends AbstractController
{
    public function __construct(private UsuarioRepository $usuarioRepository, private UnidadeRepository $unidadeRepository){}

    #[Route('/{siglaUnidade}', name: 'app_usuario')]
    public function index(string $siglaUnidade): JsonResponse
    {
        $unidade = $this->unidadeRepository->findOneBy(['sigla' => $siglaUnidade]);

        return $this->json($this->usuarioRepository->findBy(['unidade' => $unidade->getId()]));
    }
}
