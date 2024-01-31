<?php

namespace App\DataFixtures;

use App\Entity\Unidade;
use App\Entity\Usuario;
use App\Repository\UnidadeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AppFixtures extends Fixture
{
    public function __construct(private HttpClientInterface $httpClient, private UnidadeRepository $unidadeRepository){}

    public function load(ObjectManager $manager): void
    {
        //### Unidades ###//

        $response = $this->httpClient->request(
            'GET',
            'https://sisnetuno-hmlg.dadm.mb/api/om/v1/lista'
        );

        $statusCode = $response->getStatusCode();

        foreach($response->toArray() as $item){

            $unidade = new Unidade();
            $unidade->setSigla($item["sigla"]);
            $unidade->setNome($item["nome"]);
            
            if($item["sigla"] == "DAdM") {
                $unidadeDAdM = $unidade;
            }

            // Informa ao Doctrine que você deseja salvar esse novo objeto, quando for efetuado o flush.
            $manager->persist($unidade);
        }

        // Efetua as alterações no banco de dados
        $manager->flush();


        //### Usuários ###//

        $usuario = new Usuario();
        $usuario->setCpf('55114000098');
        $usuario->setNome('Alexandre Viveiros');
        $usuario->setEmail('alexandre.viveiros@marinha.mil.br');
        $usuario->setUnidade($unidadeDAdM);
        $manager->persist($usuario);

        $usuario = new Usuario();
        $usuario->setCpf('83019858011');
        $usuario->setNome('Anderson Fernandes');
        $usuario->setEmail('anderson.fernandes@marinha.mil.br');
        $usuario->setUnidade($unidadeDAdM);
        $manager->persist($usuario);

        $usuario = new Usuario();
        $usuario->setCpf('17076021072');
        $usuario->setNome('Filipe Moura');
        $usuario->setEmail('filipe.moura@marinha.mil.br');
        $usuario->setUnidade($unidadeDAdM);
        $manager->persist($usuario);

        $usuario = new Usuario();
        $usuario->setCpf('52047808073');
        $usuario->setNome('Vanderleia de Figueiredo');
        $usuario->setEmail('vanderleia@marinha.mil.br');
        $usuario->setUnidade($unidadeDAdM);
        $manager->persist($usuario);

        $usuario = new Usuario();
        $usuario->setCpf('27947170061');
        $usuario->setNome('Arlyson de Almeida');
        $usuario->setEmail('arlyson@marinha.mil.br');
        $usuario->setUnidade($unidadeDAdM);
        $manager->persist($usuario);

        $usuario = new Usuario();
        $usuario->setCpf('68204608055');
        $usuario->setNome('Paulo Silva');
        $usuario->setEmail('paulo-fernandes.pf@marinha.mil.br');
        $usuario->setUnidade($unidadeDAdM);
        $manager->persist($usuario);

        $usuario = new Usuario();
        $usuario->setCpf('89303881087');
        $usuario->setNome('Milton Cunha');  
        $usuario->setEmail('milton.cunha@marinha.mil.br');
        $usuario->setUnidade($unidadeDAdM);
        $manager->persist($usuario);

        $usuario = new Usuario();
        $usuario->setCpf('71079764003');
        $usuario->setNome('Abrahaão Silva');
        $usuario->setEmail('abrahaao.silva@marinha.mil.br');
        $usuario->setUnidade($unidadeDAdM);
        $manager->persist($usuario);

        $usuario = new Usuario();
        $usuario->setCpf('34490399030');
        $usuario->setNome('Thiago Alves');
        $usuario->setEmail('rodrigues.alves@marinha.mil.br');
        $usuario->setUnidade($unidadeDAdM);
        $manager->persist($usuario);

        $usuario = new Usuario();
        $usuario->setCpf('05060825043');
        $usuario->setNome('Diego Bastos');
        $usuario->setEmail('d.bastos@marinha.mil.br');
        $usuario->setUnidade($unidadeDAdM);
        $manager->persist($usuario);

        $usuario = new Usuario();
        $usuario->setCpf('98101504079');
        $usuario->setNome('Leonardo Freire');
        $usuario->setEmail('leonardo-gomes.freire@marinha.mil.br');
        $usuario->setUnidade($unidadeDAdM);
        $manager->persist($usuario);

        $usuario = new Usuario();
        $usuario->setCpf('02819239064');
        $usuario->setNome('Matheus Moraes');
        $usuario->setEmail('nascimento.moraes@marinha.mil.br');
        $usuario->setUnidade($unidadeDAdM);
        $manager->persist($usuario);

        $manager->flush();
    }
}
