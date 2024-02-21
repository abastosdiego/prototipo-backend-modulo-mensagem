<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\MensagemRepository;
use App\Repository\UnidadeRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/mensagem/tramite')]
class TramiteController extends AbstractController
{
    private Usuario $usuarioLogado;

    public function __construct(private EntityManagerInterface $entityManager, private MensagemRepository $mensagemRepository, private UnidadeRepository $unidadeRepository, private UsuarioRepository $usuarioRepository){
        // Pegar usuário logado //
        $idUsuario = 12;
        $this->usuarioLogado = $this->usuarioRepository->find($idUsuario);
        //$usuario->getUnidade()
    }

    #[Route('/{idMensagem}', name: 'app_tramite_new', methods: ['POST'])]
    public function criar(Request $request, int $idMensagem): JsonResponse
    {
        $mensagem = $this->mensagemRepository->find($idMensagem);

        $valores = $request->toArray();

        if(isset($valores['tramite_futuro'])) {

            foreach($valores['tramite_futuro'] as $nip) {
                $usuariosTramiteFuturo[] = $this->usuarioRepository->findOneBy(['nip' => $nip]);
            }

            $mensagem->criarTramite($this->usuarioLogado->getUnidade(), $this->usuarioLogado, $usuariosTramiteFuturo);

            // Efetua as alterações no banco de dados
            $this->entityManager->flush();

            return $this->json(
                ['mensagem' => 'Trâmite cadastrado com sucesso!']
            );
        }

    }

    #[Route('/alterar/{idMensagem}', name: 'app_tramite_change', methods: ['PUT'])]
    public function alterar(Request $request, int $idMensagem): JsonResponse
    {
        $mensagem = $this->mensagemRepository->find($idMensagem);

        $valores = $request->toArray();

        if(isset($valores['tramite_futuro'])) {

             foreach($valores['tramite_futuro'] as $nip) {
                $usuariosTramiteFuturo[] = $this->usuarioRepository->findOneBy(['nip' => $nip]);
            }

            $tramite = $mensagem->getTramite($this->usuarioLogado->getUnidade());
            $tramite->criarTramiteFuturo($usuariosTramiteFuturo);

            // Efetua as alterações no banco de dados
            $this->entityManager->flush();

            return $this->json(
                ['mensagem' => 'Trâmite alterado com sucesso!']
            );
        }
        
    }

    #[Route('/encaminhar/{idMensagem}', name: 'app_tramite_send_next', methods: ['PUT'])]
    public function encaminhar(int $idMensagem): JsonResponse
    {        
        $mensagem = $this->mensagemRepository->find($idMensagem);
        $tramite = $mensagem->getTramite($this->usuarioLogado->getUnidade());
        $tramite->encaminharProximo();

        // Efetua as alterações no banco de dados
        $this->entityManager->flush();

        return $this->json(
            ['mensagem' => 'Mensagem encaminhada para o próximo do trâmite!']
        );
    }

    #[Route('/encaminhar-para/{idMensagem}', name: 'app_tramite_send_to', methods: ['PUT'])]
    public function encaminharPara(Request $request, int $idMensagem): JsonResponse
    {
        $valores = $request->toArray();

        if(isset($valores['usuario'])) {

            $usuario = $this->usuarioRepository->findOneBy(['nip' => $valores['usuario']]);

            $mensagem = $this->mensagemRepository->find($idMensagem);
            $tramite = $mensagem->getTramite($this->usuarioLogado->getUnidade());
            $tramite->encaminharPara($usuario);

            // Efetua as alterações no banco de dados
            $this->entityManager->flush();

            return $this->json(
                ['mensagem' => 'Mensagem encaminhada para o usuário informado!']
            );

        }
    }
}
