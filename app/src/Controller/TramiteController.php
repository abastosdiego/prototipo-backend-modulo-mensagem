<?php

namespace App\Controller;

use App\UseCase\CadastrarTramite;
use App\UseCase\AlterarTramite;
use App\UseCase\EncaminharTramite;
use App\UseCase\EncaminharParaTramite;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/mensagem/tramite')]
class TramiteController extends AbstractController
{

    #[Route('/{idMensagem}', name: 'app_tramite_new', methods: ['POST'])]
    public function criar(Request $request, int $idMensagem, CadastrarTramite $cadastrarTramite): JsonResponse
    {
        $inputData = $request->toArray();
        $cadastrarTramite->executar($idMensagem, $inputData);

        return $this->json(
            ['mensagem' => 'Trâmite cadastrado com sucesso!']
        );
    }

    #[Route('/alterar/{idMensagem}', name: 'app_tramite_change', methods: ['PUT'])]
    public function alterar(Request $request, int $idMensagem, AlterarTramite $alterarTramite): JsonResponse
    {
        $inputData = $request->toArray();
        $alterarTramite->executar($idMensagem, $inputData);

        return $this->json(
            ['mensagem' => 'Trâmite alterado com sucesso!']
        );
    }

    #[Route('/encaminhar/{idMensagem}', name: 'app_tramite_send_next', methods: ['PUT'])]
    public function encaminhar(int $idMensagem, EncaminharTramite $encaminharTramite): JsonResponse
    {        
        $encaminharTramite->executar($idMensagem);

        return $this->json(
            ['mensagem' => 'Mensagem encaminhada para o próximo do trâmite!']
        );
    }

    #[Route('/encaminhar-para/{idMensagem}', name: 'app_tramite_send_to', methods: ['PUT'])]
    public function encaminharPara(Request $request, int $idMensagem, EncaminharParaTramite $encaminharParaTramite): JsonResponse
    {
        $inputData = $request->toArray();
        $encaminharParaTramite->executar($idMensagem, $inputData);

        return $this->json(
            ['mensagem' => 'Mensagem encaminhada para o usuário informado!']
        );
    }
}
