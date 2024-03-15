<?php

namespace App\DataFixtures;

use App\Entity\Unidade;
use App\Entity\Usuario;
use App\Repository\UnidadeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AppFixtures extends Fixture
{
    public function __construct(private HttpClientInterface $httpClient, private UnidadeRepository $unidadeRepository, private UserPasswordHasherInterface $passwordHasher){}

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

        // $unidadeDAdM = new Unidade();
        // $unidadeDAdM->setSigla('DAdM');
        // $unidadeDAdM->setNome('Diretoria de Administração da Marinha');

        // // Informa ao Doctrine que você deseja salvar esse novo objeto, quando for efetuado o flush.
        // $manager->persist($unidadeDAdM);

        // Efetua as alterações no banco de dados
        $manager->flush();


        //### Usuários ###//

        $senha = 'dadm123';

        $usuario = new Usuario(nip:'85988359', nome:'Alexandre Viveiros', email:'alexandre.viveiros@marinha.mil.br', unidade:$unidadeDAdM);
        $hashedPassword = $this->passwordHasher->hashPassword($usuario, $senha);
        $usuario->setPassword($hashedPassword);

        $manager->persist($usuario);

        ///

        $usuario = new Usuario(nip:'87300061', nome:'Anderson Fernandes', email:'anderson.fernandes@marinha.mil.br', unidade:$unidadeDAdM);
        $hashedPassword = $this->passwordHasher->hashPassword($usuario, $senha);
        $usuario->setPassword($hashedPassword);

        $manager->persist($usuario);

        ///

        $usuario = new Usuario(nip:'87358549', nome:'Vanderleia de Figueiredo', email:'vanderleia@marinha.mil.br', unidade:$unidadeDAdM);
        $hashedPassword = $this->passwordHasher->hashPassword($usuario, $senha);
        $usuario->setPassword($hashedPassword);

        $manager->persist($usuario);

        ///

        $usuario = new Usuario(nip:'01052331', nome:'Arlyson de Almeida', email:'arlyson@marinha.mil.br', unidade:$unidadeDAdM);
        $hashedPassword = $this->passwordHasher->hashPassword($usuario, $senha);
        $usuario->setPassword($hashedPassword);

        $manager->persist($usuario);

        ///

        $usuario = new Usuario(nip:'86798561', nome:'Milton Cunha', email:'milton.cunha@marinha.mil.br', unidade:$unidadeDAdM);
        $hashedPassword = $this->passwordHasher->hashPassword($usuario, $senha);
        $usuario->setPassword($hashedPassword);

        $manager->persist($usuario);

        ///
        
        $usuario = new Usuario(nip:'86936867', nome:'Abrahaão Silva', email:'abrahaao.silva@marinha.mil.br', unidade:$unidadeDAdM);
        $hashedPassword = $this->passwordHasher->hashPassword($usuario, $senha);
        $usuario->setPassword($hashedPassword);

        $manager->persist($usuario);

        ///

        $usuario = new Usuario(nip:'16055659', nome:'Thiago Alves', email:'rodrigues.alves@marinha.mil.br', unidade:$unidadeDAdM);
        $hashedPassword = $this->passwordHasher->hashPassword($usuario, $senha);
        $usuario->setPassword($hashedPassword);

        $manager->persist($usuario);

        ///

        $usuario = new Usuario(nip:'17090148', nome:'Diego Bastos', email:'d.bastos@marinha.mil.br', unidade:$unidadeDAdM);
        $hashedPassword = $this->passwordHasher->hashPassword($usuario, $senha);
        $usuario->setPassword($hashedPassword);

        $manager->persist($usuario);

        ///

        $usuario = new Usuario(nip:'21415021', nome:'Leonardo Freire', email:'leonardo-gomes.freire@marinha.mil.br', unidade:$unidadeDAdM);
        $hashedPassword = $this->passwordHasher->hashPassword($usuario, $senha);
        $usuario->setPassword($hashedPassword);

        $manager->persist($usuario);

        ///

        $usuario = new Usuario(nip:'23378026', nome:'Matheus Moraes', email:'nascimento.moraes@marinha.mil.br', unidade:$unidadeDAdM);
        $hashedPassword = $this->passwordHasher->hashPassword($usuario, $senha);
        $usuario->setPassword($hashedPassword);

        $manager->persist($usuario);

        $manager->flush();
    }
}
