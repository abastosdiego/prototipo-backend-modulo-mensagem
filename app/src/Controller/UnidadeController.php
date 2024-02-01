<?php

namespace App\Controller;

use App\Repository\UnidadeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/unidade')]
class UnidadeController extends AbstractController
{
    public function __construct(private UnidadeRepository $unidadeRepository){}

    #[Route('/', name: 'app_unidade', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json($this->unidadeRepository->findAll());
    }

}
